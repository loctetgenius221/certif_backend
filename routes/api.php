<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PlageHoraireController;
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
    Route::apiResource('categories', CategoryController::class)->only('store','show','update','destroy');
    Route::resource('comments', CommentController::class);
    Route::apiResource('/media', MediaController::class)->only('index','store','show','destroy');
    Route::match(['post', 'put', 'patch'], '/medias/{id}/edit', [MediaController::class, 'update'])
    ->name('media.update');
    Route::get('/nombrearticle', [CategoryController::class, 'getCategoriesWithArticleCount']);
    Route::get('/statblog', [ArticleController::class, 'getDashboardStats']);
});
Route::get('categories', [CategoryController::class, 'index']);

// Route pour les services
Route::middleware('auth:api')->group(function () {
    Route::apiResource('services', ServiceController::class);
});

Route::middleware('auth:api')->group(function () {
    // Récupérer les notifications
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);

    // Marquer une notification comme lue
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // Supprimer une notification (soft delete)
    Route::delete('/notifications/{id}', [NotificationController::class, 'deleteNotification']);
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
    Route::get('/rendezvous/medecin/{medecinId}', [RendezVousController::class, 'getRendezVousByMedecin']);
    Route::get('/medecins', [MedecinController::class, 'index']);

});

// Route pour les dossier médicales
Route::middleware('auth:api')->group(function () {
    // Route::apiResource('dossiers-medicaux', DossierMedicauxController::class)->name('index', 'store');
    route::get('dossiers-medicaux', [DossierMedicauxController::class, 'index']);
    route::get('dossiers-medicaux/store', [DossierMedicauxController::class, 'store']);
    route::get('dossiers-medicaux/{dossierMedicaux}', [DossierMedicauxController::class, 'show']);
    route::put('dossiers-medicaux/{dossierMedicaux}', [DossierMedicauxController::class, 'update']);
    route::delete('dossiers-medicaux/{dossierMedicaux}', [DossierMedicauxController::class, 'destroy']);
    Route::get('/dossier/patient/{id}', [DossierMedicauxController::class, 'getDossierByPatient']);
});

// Route pour les consultations
Route::middleware('auth:api')->group(function () {
    Route::apiResource('consultations', ConsultationsController::class);
    Route::get('consultations/patient/{patient_id}', [ConsultationsController::class, 'getConsultationByPatient']);
});

// Route pour les documents
Route::middleware('auth:api')->group(function () {
    Route::apiResource('document', DocumentsController::class);
});

Route::middleware('auth:api')->group(function () {
    // Route::apiResource('plages-horaires', PlageHoraireController::class);
    Route::get('/plagesbymedecin/{id}', [PlageHoraireController::class, 'getPlageByMedecin']);

    Route::get('plageshoraires', [PlageHoraireController::class, 'index']);
    Route::post('plageshoraires', [PlageHoraireController::class, 'store']);
    Route::get('plageshoraires/{plageHoraire}', [PlageHoraireController::class, 'show']);
    Route::put('plageshoraires/{plageHoraire}', [PlageHoraireController::class, 'update']);
    Route::delete('plageshoraires/{plageHoraire}', [PlageHoraireController::class, 'destroy']);


    Route::patch('/plages-horaires/{id}/status', [PlageHoraireController::class, 'updateStatus']);
});

// Route pour la gestion des utilisateurs
Route::middleware('auth:api')->group(function () {
    Route::get('/utilisateur/stats', [UserController::class, 'getUserStatistics']);
    Route::post('/utilisateur/{id}/block', [UserController::class, 'blockUser']);
    Route::post('/utilisateur/{id}/unblock', [UserController::class, 'unblockUser']);
    // Gestion des roles
    Route::get('/roles', [UserController::class, 'getRole']);
    Route::post('/roles', [UserController::class, 'storeRole']); // Ajouter un rôle
    Route::put('/roles/{role}', [UserController::class, 'updateRole']); // Modifier un rôle
    Route::delete('/roles/{role}', [UserController::class, 'destroyRole']);
});

// Route pour la gestion des roles et permissions
Route::middleware('auth:api')->group(function () {
    Route::get('/roles-permissions', [UserController::class, 'getRolePermissions']);
});
