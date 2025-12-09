import { useState } from 'react';
import CommentList from './CommentList';

function ArticleCard({ article, onDelete }) {
  const [showComments, setShowComments] = useState(false);

  const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    
    const date = new Date(dateString);
    
    return date.toLocaleDateString('fr-FR', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
      timeZone: 'Europe/Paris'
    });
  };

  return (
    <div className="card">
      <h3>{article.title}</h3>
      <div style={{ color: '#7f8c8d', fontSize: '0.9em', marginBottom: '0.5rem' }}>
        Par {article.author} • {formatDate(article.created_at)}
      </div>
      <p style={{ marginBottom: '1rem' }}>{article.content}</p>
      
      <div style={{ display: 'flex', gap: '0.5rem', alignItems: 'center' }}>
        <button 
          onClick={() => setShowComments(!showComments)}
          style={{ fontSize: '0.9em' }}
        >
          {showComments ? 'Masquer' : 'Afficher'} commentaires ({article.comments_count || 0})
        </button>
        
        {onDelete && (
          <button 
            onClick={() => onDelete(article.id)}
            style={{ 
              backgroundColor: '#e74c3c',
              fontSize: '0.9em'
            }}
          >
            Supprimer
          </button>
        )}
      </div>

      {showComments && (
        <div style={{ marginTop: '1rem', borderTop: '1px solid #ecf0f1', paddingTop: '1rem' }}>
          <CommentList articleId={article.id} />
        </div>
      )}
    </div>
  );
}

export default ArticleCard;

