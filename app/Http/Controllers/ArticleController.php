<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Assistant;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::all();
        return $this->customJsonResponse("Liste des articles", $articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {

        // Récupérez l'utilisateur connecté
        $user = auth()->user();

        // Vérifiez si l'utilisateur est un assistant
        if ($user->hasRole('assistant')) {
            // Récupérez l'ID de l'assistant
            $assistant = Assistant::where('user_id', $user->id)->first();

            if (!$assistant) {
                return response()->json([
                    "message" => "Assistant non trouvé.",
                ], 404);
            }

            $auteurId = $assistant->id; // L'ID de l'assistant
        } else {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à créer un article.",
            ], 403);
        }

        $article = new Article();
        $article->titre = $request->titre;
        $article->contenu = $request->contenu;
        $article->date_publication = $request->date_publication;
        $article->auteur_id = $auteurId;

        // Gestion de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $article->image = $image->store('articles', 'public');
        }
        $article->save();

        return response()->json([
            "message" => "Article créé avec succès",
            "data" => $article
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return $this->customJsonResponse("Article récupéré avec succès", $article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article->fill($request->validated());

        if ($request->hasFile('image')) {
            // Vérifier si l'ancienne image existe et la supprimer
            if (File::exists(public_path("storage/" . $article->image))) {
                File::delete(public_path("storage/" . $article->image));
            }

            // Stocker la nouvelle image
            $image = $request->file('image');
            $article->image = $image->store('articles', 'public');
        }
        $article->update();

        return response()->json([
            "message" => "Article mis à jour avec succès",
            "data" => $article
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return $this->customJsonResponse("Article supprimé avec succès", null, 200);
    }
}
