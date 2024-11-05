<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('articles')->get();
        return $this->customJsonResponse("Liste des catégories", $categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $categorie = new Category();
        $categorie->nom = $request->nom;
        $categorie->save();

        return response()->json([
            "message" => "Catégorie créée avec succès",
            "data" => $categorie
        ], 201);
    }

    public function getCategoriesWithArticleCount()
    {
        $categories = Category::withCount('articles')->get();
        return response()->json($categories);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->customJsonResponse("Catégorie récupérée avec succès", $category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->fill($request->validated());
        $category->save();

        return response()->json([
            "message" => "Catégorie mise à jour avec succès",
            "data" => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->customJsonResponse("Catégorie supprimée avec succès", null, 200);
    }
}
