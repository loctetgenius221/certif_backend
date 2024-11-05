<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::with('article')->get(); // Charger les commentaires avec l'article associé
        return $this->customJsonResponse("Liste des commentaires", $comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        // Récupérer l'utilisateur connecté
        $user = auth()->user();

        $comment = new Comment();
        $comment->content = $request->content; // Assurez-vous que le champ existe dans la requête
        $comment->user_id = $user->id; // Assurez-vous que votre modèle Comment a un champ user_id
        $comment->article_id = $request->article_id; // Assurez-vous que votre requête contient article_id

        $comment->save();

        return response()->json([
            "message" => "Commentaire créé avec succès",
            "data" => $comment
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return $this->customJsonResponse("Commentaire récupéré avec succès", $comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->content = $request->content; // Mettez à jour le contenu du commentaire
        $comment->save();

        return response()->json([
            "message" => "Commentaire mis à jour avec succès",
            "data" => $comment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return $this->customJsonResponse("Commentaire supprimé avec succès", null, 200);
    }
}
