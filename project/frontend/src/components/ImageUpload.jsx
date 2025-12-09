import { useState } from 'react';
import { uploadImage } from '../services/api';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';

function ImageUpload() {
  const [selectedFile, setSelectedFile] = useState(null);
  const [uploading, setUploading] = useState(false);
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [uploadedImage, setUploadedImage] = useState(null);

  const handleFileSelect = (e) => {
    const file = e.target.files[0];
    if (file) {
      setSelectedFile(file);
      setMessage('');
      setError('');
      
      const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
      setMessage(`Fichier sélectionné : ${file.name} (${sizeMB} MB)`);
    }
  };

  const handleUpload = async () => {
    if (!selectedFile) {
      setError('Veuillez sélectionner une image');
      return;
    }

    setUploading(true);
    setError('');
    setMessage('');

    const formData = new FormData();
    formData.append('image', selectedFile);

    try {
      const response = await uploadImage(formData);
      const data = response.data;
      
      // Message avec les économies de taille
      const originalKB = (data.original_size / 1024).toFixed(0);
      const optimizedKB = (data.size / 1024).toFixed(0);
      setMessage(`✅ Image optimisée ! ${originalKB} KB → ${optimizedKB} KB (${data.savings_percent}% économisé)`);
      setUploadedImage(data);
      setSelectedFile(null);
    } catch (err) {
      if (err.response?.status === 413) {
        setError('❌ Erreur 413 : Image trop volumineuse ! La limite est de 20MB.');
      } else {
        setError(`❌ Erreur lors de l'upload : ${err.response?.data?.error || err.message}`);
      }
      console.error('Upload error:', err);
    } finally {
      setUploading(false);
    }
  };

  return (
    <div className="card">
      <h3>📸 Upload d'Image (avec optimisation)</h3>
      <p style={{ color: '#7f8c8d', fontSize: '0.9em', marginBottom: '1rem' }}>
        Les images sont automatiquement redimensionnées, compressées et converties en WebP
      </p>

      <div style={{ marginBottom: '1rem' }}>
        <input
          type="file"
          accept="image/*"
          onChange={handleFileSelect}
          style={{ marginBottom: '0.5rem' }}
        />
      </div>

      {message && !error && (
        <div style={{ 
          padding: '0.8rem', 
          backgroundColor: '#d4edda', 
          color: '#155724',
          borderRadius: '4px',
          marginBottom: '1rem',
          fontSize: '0.9em'
        }}>
          {message}
        </div>
      )}

      {error && (
        <div className="error" style={{ marginBottom: '1rem', fontSize: '0.9em' }}>
          {error}
        </div>
      )}

      {/* Aperçu de l'image optimisée avec variantes */}
      {uploadedImage && (
        <div style={{ 
          padding: '1rem', 
          backgroundColor: '#f8f9fa',
          borderRadius: '4px',
          marginBottom: '1rem',
        }}>
          <strong>📊 Résultat de l'optimisation :</strong>
          
          {/* Image principale avec lazy loading */}
          <div style={{ marginTop: '1rem', marginBottom: '1rem' }}>
            <picture>
              {uploadedImage.webp_url && (
                <source 
                  srcSet={`${API_URL}${uploadedImage.webp_url}`} 
                  type="image/webp" 
                />
              )}
              <img
                src={`${API_URL}${uploadedImage.url}`}
                alt="Image uploadée"
                loading="lazy"
                width={uploadedImage.variants?.medium?.width || 600}
                height={uploadedImage.variants?.medium?.height || 400}
                style={{ 
                  maxWidth: '100%', 
                  height: 'auto',
                  borderRadius: '4px',
                  border: '1px solid #ddd'
                }}
              />
            </picture>
          </div>
          
          {/* Tableau des variantes */}
          <div style={{ fontSize: '0.85em' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse' }}>
              <thead>
                <tr style={{ backgroundColor: '#e9ecef' }}>
                  <th style={{ padding: '0.5rem', textAlign: 'left' }}>Taille</th>
                  <th style={{ padding: '0.5rem', textAlign: 'center' }}>Dimensions</th>
                  <th style={{ padding: '0.5rem', textAlign: 'right' }}>JPG/PNG</th>
                  <th style={{ padding: '0.5rem', textAlign: 'right' }}>WebP</th>
                </tr>
              </thead>
              <tbody>
                <tr style={{ borderBottom: '1px solid #dee2e6' }}>
                  <td style={{ padding: '0.5rem' }}>📱 Original</td>
                  <td style={{ padding: '0.5rem', textAlign: 'center' }}>
                    {uploadedImage.original?.width}×{uploadedImage.original?.height}
                  </td>
                  <td style={{ padding: '0.5rem', textAlign: 'right' }}>
                    {(uploadedImage.original_size / 1024).toFixed(0)} KB
                  </td>
                  <td style={{ padding: '0.5rem', textAlign: 'right' }}>-</td>
                </tr>
                {uploadedImage.variants && Object.entries(uploadedImage.variants).map(([name, variant]) => (
                  <tr key={name} style={{ borderBottom: '1px solid #dee2e6' }}>
                    <td style={{ padding: '0.5rem' }}>
                      {name === 'thumbnail' ? '🖼️' : name === 'medium' ? '📷' : '🖥️'} {name}
                    </td>
                    <td style={{ padding: '0.5rem', textAlign: 'center' }}>
                      {variant.width}×{variant.height}
                    </td>
                    <td style={{ padding: '0.5rem', textAlign: 'right' }}>
                      {(variant.size / 1024).toFixed(0)} KB
                    </td>
                    <td style={{ padding: '0.5rem', textAlign: 'right', color: '#28a745' }}>
                      {variant.webp_size ? `${(variant.webp_size / 1024).toFixed(0)} KB` : '-'}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      <button 
        onClick={handleUpload} 
        disabled={!selectedFile || uploading}
        style={{ marginRight: '0.5rem' }}
      >
        {uploading ? '⏳ Optimisation en cours...' : '📤 Uploader & Optimiser'}
      </button>

      {selectedFile && (
        <button 
          onClick={() => {
            setSelectedFile(null);
            setMessage('');
            setError('');
          }}
          style={{ backgroundColor: '#95a5a6' }}
        >
          Annuler
        </button>
      )}

      <div style={{ 
        marginTop: '1.5rem', 
        padding: '1rem', 
        backgroundColor: '#d1ecf1',
        borderRadius: '4px',
        fontSize: '0.85em',
        color: '#0c5460'
      }}>
        <strong>🚀 Optimisations PERF-002 appliquées :</strong>
        <ul style={{ marginTop: '0.5rem', marginBottom: 0, paddingLeft: '1.5rem' }}>
          <li>Redimensionnement : thumbnail (300px), medium (600px), large (1200px)</li>
          <li>Compression : qualité 80% (JPG) / 75% (WebP)</li>
          <li>Format WebP : ~30% plus léger que JPG</li>
          <li>Lazy loading : images chargées uniquement quand visibles</li>
          <li>width/height : évite le layout shift (CLS)</li>
        </ul>
      </div>
    </div>
  );
}

export default ImageUpload;

