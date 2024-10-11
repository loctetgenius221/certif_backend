<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\ConsultationsController;
use App\Http\Controllers\DossierMedicauxController;



// Routes pour inscription
Route::post('/register/patient', [AuthController::class, 'registerPatient']);
Route::post('/register/medecin', [AuthController::class, 'registerMedecin']);
Route::post('/register/assistant', [AuthController::class, 'registerAssistant']);
// Route pour la connexion
Route::post('/login', [AuthController::class, "login"]);
Route::middleware("auth:api")->group(
    function () {
        Route::get("profile", [AuthController::class, "profile"]);
        Route::post("/logout", [AuthController::class, "logout"]);
        Route::get("/refresh", [AuthController::class, "refresh"]);
    }
);

Route::middleware("auth:api")->group(
    function () {
        Route::get('/users/{id}', [MedecinController::class, 'show']);
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
Route::middleware('auth:api')->group(function () {
    Route::apiResource('services', ServiceController::class);
});

// Route pour les rendez-vous
Route::middleware('auth:api')->group(function () {
    // Route::apiResource('rendezvous', RendezVousController::class);
    Route::get('rendezvous', [RendezVousController::class, 'index']);
    Route::post('rendezvous', [RendezVousController::class, 'store']);
    Route::get('rendezvous/{rendezVous}', [RendezVousController::class, 'show']);
    Route::put('rendezvous/{rendezVous}', [RendezVousController::class, 'update']);
    Route::delete('rendezvous/{rendezVous}', [RendezVousController::class, 'destroy']);
    Route::post('rendezvous/{rendezVous}', [RendezVousController::class, 'restore']);
    Route::put('/rendezvous/{rendezVous}/status', [RendezVousController::class, 'changeStatus']);
    Route::get('/rendezvous/patient/{patientId}', [RendezVousController::class, 'getRendezVousByPatient']);

});

// Route pour les dossier médicales
Route::middleware('auth:api')->group(function () {
    // Route::apiResource('dossiers-medicaux', DossierMedicauxController::class)->name('index', 'store');
    route::get('dossiers-medicaux', [DossierMedicauxController::class, 'index']);
    route::get('dossiers-medicaux/store', [DossierMedicauxController::class, 'store']);
    route::get('dossiers-medicaux/{dossierMedicaux}', [DossierMedicauxController::class, 'show']);
    route::put('dossiers-medicaux/{dossierMedicaux}', [DossierMedicauxController::class, 'update']);
    route::delete('dossiers-medicaux/{dossierMedicaux}', [DossierMedicauxController::class, 'destroy']);

});

// Route pour les consultations
Route::middleware('auth:api')->group(function () {
    Route::apiResource('consultations', ConsultationsController::class);
});

// Route pour les documents
Route::middleware('auth:api')->group(function () {
    Route::apiResource('document', DocumentsController::class);
});
