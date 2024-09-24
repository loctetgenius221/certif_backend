<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RendezVousController;



// Routes pour inscription
Route::post('/register/patient', [AuthController::class, 'registerPatient']);
Route::post('/register/medecin', [AuthController::class, 'registerMedecin']);
Route::post('/register/assistant', [AuthController::class, 'registerAssistant']);
// Route pour la connexion
Route::post("login", [AuthController::class, "login"]);
Route::middleware("auth")->group(
    function () {
        Route::get("profile", [AuthController::class, "profile"]);
        Route::get("/logout", [AuthController::class, "logout"]);
        Route::get("/refresh", [AuthController::class, "refresh"]);
    }
);

// Route pour les articles
// Groupe de routes protégé par le middleware JWT
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index'); // Liste des articles
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show'); // Afficher un article spécifique

Route::middleware(['auth:api'])->group(function () {
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store'); // Création d'un article
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update'); // Mise à jour d'un article
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy'); // Suppression d'un article
});

// Route pour les services
Route::middleware('auth')->group(function () {
    Route::apiResource('services', ServiceController::class);
});

// Route pour les rendez-vous
Route::middleware('auth')->group(function () {
    Route::apiResource('rendezvous', RendezVousController::class);
    Route::put('/rendezvous/{rendezVous}/status', [RendezVousController::class, 'changeStatus']);
});
