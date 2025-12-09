<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request)
    {
        $articles = Article::all();

        $articles = $articles->map(function ($article) use ($request) {
            if ($request->has('performance_test')) {
                usleep(30000); // 30ms par article pour simuler le coût du N+1
            }

            return [
                'id' => $article->id,
                'title' => $article->title,
                'content' => substr($article->content, 0, 200) . '...',
                'author' => $article->author->name,
                'comments_count' => $article->comments->count(),
                'published_at' => $article->published_at,
                'created_at' => $article->created_at,
            ];
        });

        return response()->json($articles);
    }

    /**
     * Display the specified article.
     */
    public function show($id)
    {
        $article = Article::with(['author', 'comments.user'])->findOrFail($id);

        return response()->json([
            'id' => $article->id,
            'title' => $article->title,
            'content' => $article->content,
            'author' => $article->author->name,
            'author_id' => $article->author->id,
            'image_path' => $article->image_path,
            'published_at' => $article->published_at,
            'created_at' => $article->created_at,
            'comments' => $article->comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user' => $comment->user->name,
                    'created_at' => $comment->created_at,
                ];
            }),
        ]);
    }

    /**
     * Search articles.
     * 
     * Utilise Eloquent ORM avec whereRaw et bindings pour :
     * - Protection contre les injections SQL (paramètres échappés)
     * - Recherche insensible aux accents (COLLATE utf8mb4_0900_ai_ci)
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([]);
        }

        // Utilisation d'Eloquent avec whereRaw pour la collation accent-insensitive
        // Les paramètres sont automatiquement échappés (protection injection SQL)
        $searchTerm = '%' . $query . '%';
        
        $articles = Article::whereRaw(
            "title COLLATE utf8mb4_0900_ai_ci LIKE ? OR content COLLATE utf8mb4_0900_ai_ci LIKE ?",
            [$searchTerm, $searchTerm]
        )->get();

        return response()->json($articles->map(function ($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'content' => substr($article->content, 0, 200),
                'published_at' => $article->published_at,
            ];
        }));
    }

    /**
     * Store a newly created article.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'author_id' => 'required|exists:users,id',
            'image_path' => 'nullable|string',
        ]);

        $article = Article::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'author_id' => $validated['author_id'],
            'image_path' => $validated['image_path'] ?? null,
            'published_at' => now(),
        ]);

        return response()->json($article, 201);
    }

    /**
     * Update the specified article.
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|max:255',
            'content' => 'sometimes|required',
        ]);

        $article->update($validated);

        return response()->json($article);
    }

    /**
     * Remove the specified article.
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(['message' => 'Article deleted successfully']);
    }
}

