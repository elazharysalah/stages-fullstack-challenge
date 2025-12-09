/**
 * Composant OptimizedImage - Affichage d'images optimisées
 * 
 * Fonctionnalités PERF-002 :
 * - Lazy loading natif (loading="lazy")
 * - Attributs width/height pour éviter le layout shift (CLS)
 * - srcset pour images responsive
 * - <picture> avec WebP + fallback JPG/PNG
 * - Placeholder pendant le chargement
 */

import { useState } from 'react';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';

/**
 * Construit l'URL complète d'une image.
 * @param {string} path - Chemin relatif de l'image
 * @returns {string} URL complète
 */
const buildImageUrl = (path) => {
  if (!path) return null;
  if (path.startsWith('http')) return path;
  return `${API_URL}${path.startsWith('/') ? '' : '/storage/'}${path}`;
};

/**
 * Composant d'image optimisée avec lazy loading, srcset et WebP.
 * 
 * @param {Object} props
 * @param {string} props.src - URL de l'image principale (medium)
 * @param {string} props.alt - Texte alternatif
 * @param {Object} props.variants - Variantes d'image {thumbnail, medium, large}
 * @param {number} props.width - Largeur de l'image
 * @param {number} props.height - Hauteur de l'image
 * @param {string} props.className - Classes CSS additionnelles
 * @param {Object} props.style - Styles inline additionnels
 * @param {string} props.sizes - Attribut sizes pour srcset (défaut: responsive)
 */
function OptimizedImage({ 
  src, 
  alt = '', 
  variants = null,
  width = 600, 
  height = 400,
  className = '',
  style = {},
  sizes = '(max-width: 600px) 100vw, (max-width: 1200px) 50vw, 600px'
}) {
  const [isLoaded, setIsLoaded] = useState(false);
  const [hasError, setHasError] = useState(false);

  // Si pas d'image, afficher un placeholder
  if (!src && !variants) {
    return (
      <div 
        className={className}
        style={{
          width: '100%',
          height: 'auto',
          aspectRatio: `${width}/${height}`,
          backgroundColor: '#f0f0f0',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          color: '#999',
          fontSize: '0.9em',
          borderRadius: '4px',
          ...style
        }}
      >
        📷 Pas d'image
      </div>
    );
  }

  // Construire les URLs
  const mainUrl = buildImageUrl(src || variants?.medium?.url);
  
  // Construire le srcset pour images responsive
  let srcSet = null;
  let webpSrcSet = null;
  
  if (variants) {
    const srcSetParts = [];
    const webpSrcSetParts = [];
    
    if (variants.thumbnail) {
      srcSetParts.push(`${buildImageUrl(variants.thumbnail.url)} ${variants.thumbnail.width}w`);
      if (variants.thumbnail.webp_url) {
        webpSrcSetParts.push(`${buildImageUrl(variants.thumbnail.webp_url)} ${variants.thumbnail.width}w`);
      }
    }
    if (variants.medium) {
      srcSetParts.push(`${buildImageUrl(variants.medium.url)} ${variants.medium.width}w`);
      if (variants.medium.webp_url) {
        webpSrcSetParts.push(`${buildImageUrl(variants.medium.webp_url)} ${variants.medium.width}w`);
      }
    }
    if (variants.large) {
      srcSetParts.push(`${buildImageUrl(variants.large.url)} ${variants.large.width}w`);
      if (variants.large.webp_url) {
        webpSrcSetParts.push(`${buildImageUrl(variants.large.webp_url)} ${variants.large.width}w`);
      }
    }
    
    if (srcSetParts.length > 0) {
      srcSet = srcSetParts.join(', ');
    }
    if (webpSrcSetParts.length > 0) {
      webpSrcSet = webpSrcSetParts.join(', ');
    }
    
    // Utiliser les dimensions de medium par défaut
    if (variants.medium) {
      width = variants.medium.width;
      height = variants.medium.height;
    }
  }

  // Construire l'URL WebP
  const webpUrl = variants?.medium?.webp_url 
    ? buildImageUrl(variants.medium.webp_url)
    : null;

  // Gestion d'erreur
  if (hasError) {
    return (
      <div 
        className={className}
        style={{
          width: '100%',
          height: 'auto',
          aspectRatio: `${width}/${height}`,
          backgroundColor: '#fee',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          color: '#c00',
          fontSize: '0.9em',
          borderRadius: '4px',
          ...style
        }}
      >
        ❌ Erreur de chargement
      </div>
    );
  }

  const imageStyles = {
    width: '100%',
    height: 'auto',
    display: 'block',
    borderRadius: '4px',
    transition: 'opacity 0.3s ease',
    opacity: isLoaded ? 1 : 0,
    ...style
  };

  const placeholderStyles = {
    position: 'absolute',
    top: 0,
    left: 0,
    width: '100%',
    height: '100%',
    backgroundColor: '#f0f0f0',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    borderRadius: '4px',
    transition: 'opacity 0.3s ease',
    opacity: isLoaded ? 0 : 1,
    pointerEvents: 'none',
  };

  return (
    <div 
      className={className}
      style={{ 
        position: 'relative',
        width: '100%',
        aspectRatio: `${width}/${height}`,
      }}
    >
      {/* Placeholder pendant le chargement */}
      <div style={placeholderStyles}>
        <span style={{ color: '#999', fontSize: '0.9em' }}>⏳ Chargement...</span>
      </div>

      {/* Image avec picture pour WebP + fallback */}
      <picture>
        {/* Source WebP (format moderne, plus léger) */}
        {webpSrcSet && (
          <source
            type="image/webp"
            srcSet={webpSrcSet}
            sizes={sizes}
          />
        )}
        {webpUrl && !webpSrcSet && (
          <source
            type="image/webp"
            srcSet={webpUrl}
          />
        )}
        
        {/* Source JPG/PNG avec srcset pour responsive */}
        {srcSet && (
          <source
            srcSet={srcSet}
            sizes={sizes}
          />
        )}
        
        {/* Image fallback */}
        <img
          src={mainUrl}
          alt={alt}
          width={width}
          height={height}
          loading="lazy"
          decoding="async"
          onLoad={() => setIsLoaded(true)}
          onError={() => setHasError(true)}
          style={imageStyles}
        />
      </picture>
    </div>
  );
}

export default OptimizedImage;

