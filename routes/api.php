<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// Routes pour inscription
Route::post('/register/patient', [AuthController::class, 'registerPatient']);
Route::post('/register/medecin', [AuthController::class, 'registerMedecin']);
Route::post('/register/assistant', [AuthController::class, 'registerAssistant']);
Route::post("login", [AuthController::class, "login"]);

Route::middleware("auth")->group(
    function () {
        Route::get("profile", [AuthController::class, "profile"]);
        Route::get("/logout", [AuthController::class, "logout"]);
        Route::get("/refresh", [AuthController::class, "refresh"]);
    }
);

