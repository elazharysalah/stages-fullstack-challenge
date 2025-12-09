<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    /**
     * Service d'optimisation d'images.
     * 
     * @var ImageService
     */
    protected $imageService;

    /**
     * Constructeur avec injection du service.
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Handle image upload avec optimisation automatique.
     * 
     * Fonctionnalités :
     * - Redimensionnement automatique (thumbnail 300px, medium 600px, large 1200px)
     * - Compression qualité 80%
     * - Génération WebP (format moderne, ~30% plus léger)
     * - Préservation du ratio d'aspect
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'No image provided'], 400);
        }

        $image = $request->file('image');
        $originalSize = $image->getSize();

        try {
            // Traiter et optimiser l'image
            $result = $this->imageService->processUpload($image);
            
            // Calculer les économies de taille
            $mediumSize = $result['variants']['medium']['size'] ?? $originalSize;
            $savings = round((1 - ($mediumSize / $originalSize)) * 100);
            
            return response()->json([
                'message' => 'Image uploaded and optimized successfully',
                'path' => $result['path'],
                'url' => $result['url'],
                'webp_url' => $result['webp_url'],
                'size' => $mediumSize,
                'original_size' => $originalSize,
                'savings_percent' => $savings,
                'variants' => $result['variants'],
                'original' => $result['original'],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Image processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an uploaded image et toutes ses variantes.
     */
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $path = $request->input('path');

        try {
            // Supprimer toutes les variantes (thumbnail, medium, large, webp)
            $this->imageService->deleteAllVariants($path);
            
            // Supprimer aussi le fichier spécifié au cas où
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            
            return response()->json(['message' => 'Image and all variants deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Delete failed: ' . $e->getMessage()], 500);
        }
    }
}

