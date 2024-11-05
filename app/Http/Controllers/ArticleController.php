<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Assistant;
use App\Models\Categorie; // Assurez-vous d'importer le modèle Categorie
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with(['categorie'])->get();
        return $this->customJsonResponse("Liste des articles", $articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        // Récupérez l'utilisateur connecté
        $user = auth()->user();

        // Vérifiez si l'utilisateur est un assistant ou un administrateur
        if ($user->hasRole(['assistant', 'administrateur'])) {
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
        $article->categorie_id = $request->categorie_id; // Assurez-vous que la catégorie est présente
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
        // Vérifiez que l'utilisateur est autorisé à mettre à jour
        if (auth()->user()->cannot('update', $article)) {
            return response()->json([
                "message" => "Vous n'êtes pas autorisé à mettre à jour cet article.",
            ], 403);
        }

        // Mettre à jour les champs valides
        $article->fill($request->validated());
        $article->categorie_id = $request->categorie_id; // Mettre à jour le champ categorie_id

        if ($request->hasFile('image')) {
            // Vérifier si l'ancienne image existe et la supprimer
            if (File::exists(public_path("storage/" . $article->image))) {
                File::delete(public_path("storage/" . $article->image));
            }

            // Stocker la nouvelle image
            $image = $request->file('image');
            $article->image = $image->store('articles', 'public');
        }

        $article->save(); // Utilisez save() au lieu de update() après fill()

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

        // Supprimer l'image si elle existe
        if (File::exists(public_path("storage/" . $article->image))) {
            File::delete(public_path("storage/" . $article->image));
        }

        $article->delete();
        return $this->customJsonResponse("Article supprimé avec succès", null, 200);
    }
}
