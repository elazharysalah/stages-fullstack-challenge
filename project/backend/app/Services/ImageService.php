<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ImageService
{
    /**
     * Configurations des tailles d'images à générer.
     * 
     * @var array
     */
    protected $sizes = [
        'thumbnail' => ['width' => 300, 'height' => 200],
        'medium' => ['width' => 600, 'height' => 400],
        'large' => ['width' => 1200, 'height' => 800],
    ];

    /**
     * Qualité de compression (0-100).
     * 80% offre un bon compromis taille/qualité.
     * 
     * @var int
     */
    protected $quality = 80;

    /**
     * Qualité WebP (0-100).
     * WebP permet une qualité plus basse pour un résultat similaire.
     * 
     * @var int
     */
    protected $webpQuality = 75;

    /**
     * Traite et optimise une image uploadée.
     * 
     * @param UploadedFile $file
     * @return array Informations sur les images générées
     */
    public function processUpload(UploadedFile $file): array
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());
        $baseName = Str::random(20);
        
        // Charger l'image source
        $sourceImage = $this->createImageFromFile($file->getRealPath(), $extension);
        
        if (!$sourceImage) {
            throw new \Exception('Format d\'image non supporté');
        }

        // Obtenir les dimensions originales
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);
        
        $results = [
            'original' => [
                'width' => $originalWidth,
                'height' => $originalHeight,
                'size' => $file->getSize(),
            ],
            'variants' => [],
        ];

        // Générer chaque taille
        foreach ($this->sizes as $sizeName => $dimensions) {
            $variant = $this->generateVariant(
                $sourceImage,
                $originalWidth,
                $originalHeight,
                $dimensions['width'],
                $dimensions['height'],
                $baseName,
                $sizeName,
                $extension
            );
            
            $results['variants'][$sizeName] = $variant;
        }

        // Libérer la mémoire
        imagedestroy($sourceImage);

        // Définir le chemin principal (utiliser medium comme défaut)
        $results['path'] = $results['variants']['medium']['path'];
        $results['url'] = $results['variants']['medium']['url'];
        $results['webp_url'] = $results['variants']['medium']['webp_url'] ?? null;

        return $results;
    }

    /**
     * Génère une variante de l'image (redimensionnée + compressée + WebP).
     * 
     * @param resource $sourceImage Image source GD
     * @param int $originalWidth Largeur originale
     * @param int $originalHeight Hauteur originale
     * @param int $maxWidth Largeur max cible
     * @param int $maxHeight Hauteur max cible
     * @param string $baseName Nom de base du fichier
     * @param string $sizeName Nom de la taille (thumbnail, medium, large)
     * @param string $extension Extension originale
     * @return array Informations sur la variante
     */
    protected function generateVariant(
        $sourceImage,
        int $originalWidth,
        int $originalHeight,
        int $maxWidth,
        int $maxHeight,
        string $baseName,
        string $sizeName,
        string $extension
    ): array {
        // Calculer les nouvelles dimensions en préservant le ratio
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        
        // Ne pas agrandir les images plus petites
        if ($ratio > 1) {
            $ratio = 1;
        }
        
        $newWidth = (int) round($originalWidth * $ratio);
        $newHeight = (int) round($originalHeight * $ratio);

        // Créer l'image redimensionnée
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Préserver la transparence pour PNG
        if ($extension === 'png') {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
            imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Redimensionner avec qualité (resampling)
        imagecopyresampled(
            $resizedImage,
            $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );

        // Générer les noms de fichiers
        $jpgFilename = "{$baseName}_{$sizeName}.jpg";
        $webpFilename = "{$baseName}_{$sizeName}.webp";
        
        $jpgPath = "images/{$jpgFilename}";
        $webpPath = "images/{$webpFilename}";

        // Sauvegarder en JPG/PNG compressé
        $tempJpg = tempnam(sys_get_temp_dir(), 'img');
        if ($extension === 'png') {
            // Pour PNG, garder le format pour la transparence
            $pngFilename = "{$baseName}_{$sizeName}.png";
            $pngPath = "images/{$pngFilename}";
            imagepng($resizedImage, $tempJpg, 8); // Compression PNG (0-9)
            Storage::disk('public')->put($pngPath, file_get_contents($tempJpg));
            $mainPath = $pngPath;
            $mainUrl = "/storage/{$pngPath}";
        } else {
            imagejpeg($resizedImage, $tempJpg, $this->quality);
            Storage::disk('public')->put($jpgPath, file_get_contents($tempJpg));
            $mainPath = $jpgPath;
            $mainUrl = "/storage/{$jpgPath}";
        }
        $mainSize = filesize($tempJpg);
        unlink($tempJpg);

        // Générer la version WebP
        $webpUrl = null;
        $webpSize = null;
        if (function_exists('imagewebp')) {
            $tempWebp = tempnam(sys_get_temp_dir(), 'webp');
            imagewebp($resizedImage, $tempWebp, $this->webpQuality);
            Storage::disk('public')->put($webpPath, file_get_contents($tempWebp));
            $webpSize = filesize($tempWebp);
            $webpUrl = "/storage/{$webpPath}";
            unlink($tempWebp);
        }

        // Libérer la mémoire
        imagedestroy($resizedImage);

        return [
            'path' => $mainPath,
            'url' => $mainUrl,
            'webp_path' => $webpPath,
            'webp_url' => $webpUrl,
            'width' => $newWidth,
            'height' => $newHeight,
            'size' => $mainSize,
            'webp_size' => $webpSize,
        ];
    }

    /**
     * Crée une ressource image GD à partir d'un fichier.
     * 
     * @param string $path Chemin du fichier
     * @param string $extension Extension du fichier
     * @return resource|false
     */
    protected function createImageFromFile(string $path, string $extension)
    {
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return imagecreatefromjpeg($path);
            case 'png':
                return imagecreatefrompng($path);
            case 'gif':
                return imagecreatefromgif($path);
            case 'webp':
                if (function_exists('imagecreatefromwebp')) {
                    return imagecreatefromwebp($path);
                }
                return false;
            default:
                return false;
        }
    }

    /**
     * Supprime toutes les variantes d'une image.
     * 
     * @param string $basePath Chemin de base (ex: images/abc123_medium.jpg)
     * @return void
     */
    public function deleteAllVariants(string $basePath): void
    {
        // Extraire le nom de base
        $pathInfo = pathinfo($basePath);
        $filename = $pathInfo['filename'];
        
        // Retirer le suffixe de taille (_thumbnail, _medium, _large)
        $baseName = preg_replace('/_(thumbnail|medium|large)$/', '', $filename);
        
        $directory = $pathInfo['dirname'];
        
        // Supprimer toutes les variantes
        foreach ($this->sizes as $sizeName => $dimensions) {
            $patterns = [
                "{$directory}/{$baseName}_{$sizeName}.jpg",
                "{$directory}/{$baseName}_{$sizeName}.png",
                "{$directory}/{$baseName}_{$sizeName}.webp",
            ];
            
            foreach ($patterns as $pattern) {
                if (Storage::disk('public')->exists($pattern)) {
                    Storage::disk('public')->delete($pattern);
                }
            }
        }
    }
}

