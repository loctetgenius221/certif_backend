<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Assistant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Categorie; // Assurez-vous d'importer le modèle Categorie

class ArticleController extends Controller
{
    public function getDashboardStats()
    {
        try {
            // Récupération des comptes avec des requêtes optimisées
            $stats = [
                'articles' => Article::count(),
                'categories' => Category::count(),
                'comments' => Comment::count(),
                'media' => Media::count(),
            ];

            // Retourner la réponse JSON
            return $this->customJsonResponse("Les stats", $stats);
        } catch (\Exception $e) {
            // Log de l'erreur pour le débogage
            \Log::error('Erreur lors de la récupération des statistiques du tableau de bord: ' . $e->getMessage());

            // Retourner une réponse d'erreur
            return response()->json([
                'message' => 'Erreur lors de la récupération des statistiques.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
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

        // Vérifiez si l'utilisateur est un assistant, un administrateur ou un auteur
        if ($user->hasRole(['assistant', 'administrateur'])) {
            // Récupérez l'ID de l'assistant ou de l'administrateur
            $auteurId = $user->hasRole('administrateur') ? $user->id : Assistant::where('user_id', $user->id)->first()->id;
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
        $article->statut = $request->statut;

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
