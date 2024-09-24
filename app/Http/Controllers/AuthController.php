<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    // Méthode pour inscrire un patient
    public function registerPatient(Request $request) {
        $validator = validator($request->all(), [
            "nom" => ["required", "string"],
            "prenom" => ["required", "string"],
            "email" => ["required", "string", "email", "unique:users"],
            "password" => ["required"],
            "dateNaissance" => ["required", "date"],
            "telephone" => ["required", "string"],
            "sexe" => ["required", "in:masculin,féminin"],
            "photo_profil" => ["nullable", "string"],
            "adresse" => ["nullable", "string"],
        ]);

        // Gestion des erreurs de validation
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        // Enregistrement de l'utilisateur (patient)
        $user = User::create([
            "nom" => $request->nom,
            "prenom" => $request->prenom,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "dateNaissance" => $request->dateNaissance,
            "telephone" => $request->telephone,
            "sexe" => $request->sexe,
            "photo_profil" => $request->photo_profil,
            "adresse" => $request->adresse
        ]);

        // Assignation du rôle "patient"
        $user->assignRole('patient');

        return response()->json([
            "status" => true,
            "message" => "Patient inscrit avec succès",
            "data" => [
                "id" => $user->id,
                "nom" => $user->nom,
                "prenom" => $user->prenom,
                "email" => $user->email,
                "role" => $user->getRoleNames()
            ]
        ]);
    }

    // Méthode pour inscrire un médecin
    public function registerMedecin(Request $request) {
        // Validation
        $validator = validator($request->all(), [
            "nom" => ["required", "string"],
            "prenom" => ["required", "string"],
            "email" => ["required", "string", "email", "unique:users"],
            "password" => ["required"],
            "dateNaissance" => ["required", "date"],
            "telephone" => ["required", "string"],
            "sexe" => ["required", "in:masculin,féminin"],
            "photo_profil" => ["nullable", "string"],
            "adresse" => ["nullable", "string"],
        ]);

        // Gestion des erreurs de validation
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        // Enregistrement de l'utilisateur (médecin)
        $user = User::create([
            "nom" => $request->nom,
            "prenom" => $request->prenom,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "dateNaissance" => $request->dateNaissance,
            "telephone" => $request->telephone,
            "sexe" => $request->sexe,
            "photo_profil" => $request->photo_profil,
            "adresse" => $request->adresse
        ]);

        // Assignation du rôle "médecin"
        $user->assignRole('médecin');

        return response()->json([
            "status" => true,
            "message" => "Médecin inscrit avec succès",
        ]);
    }

    // Méthode pour inscrire un assistant
    public function registerAssistant(Request $request) {
        // Validation
        $validator = validator($request->all(), [
            "nom" => ["required", "string"],
            "prenom" => ["required", "string"],
            "email" => ["required", "string", "email", "unique:users"],
            "password" => ["required"],
            "dateNaissance" => ["required", "date"],
            "telephone" => ["required", "string"],
            "sexe" => ["required", "in:masculin,féminin"],
            "photo_profil" => ["nullable", "string"],
            "adresse" => ["nullable", "string"],
        ]);

        // Gestion des erreurs de validation
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        // Enregistrement de l'utilisateur (assistant)
        $user = User::create([
            "nom" => $request->nom,
            "prenom" => $request->prenom,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "dateNaissance" => $request->dateNaissance,
            "telephone" => $request->telephone,
            "sexe" => $request->sexe,
            "photo_profil" => $request->photo_profil,
            "adresse" => $request->adresse
        ]);

        // Assignation du rôle "assistant"
        $user->assignRole('assistant');

        return response()->json([
            "status" => true,
            "message" => "Assistant inscrit avec succès",
        ]);
    }

    // Méthode pour la connexion
    public function login(Request $request)
    {
        // Validation des données
        $validator = validator($request->all(), [
            "email" => ["required", "email", "string"],
            "password" => ["required", "string"]
        ]);

        // Gestion des erreurs de validation
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        // Récupération des informations d'identification
        $credentials = $request->only(["email", "password"]);
        $token = auth()->attempt($credentials);

        // Vérification des informations de connexion
        if (!$token) {
            return response()->json(["message" => "Informations de connexion incorrectes"], 401);
        }

        // Récupération des informations de l'utilisateur
        $user = auth()->user();

        return response()->json([
            "access_token" => $token,
            "token_type" => "bearer",
            "user" => [
                "id" => $user->id,
                "nom" => $user->nom,
                "prenom" => $user->prenom,
                "email" => $user->email,
                "role" => $user->getRoleNames()
            ],
            "expires_in" => env("JWT_TTL") * 60 . " seconds"
        ]);
    }

    // Méthode pour la déconnexion
    public function logout()
    {
        auth()->logout();
        return response()->json(["message" => "Déconnexion réussie"]);
    }

    // Méthode pour le refresh
    public function refresh()
    {
        $token = auth()->refresh();
        return response()->json([
            "access_token" => $token,
            "token_type" => "bearer",
            "user" => auth()->user(),
            "expires_in" => env("JWT_TTL") * 60 . " seconds"
        ]);
    }

    // Méthode pour le profil de l'utilisateur
    public function profile()
    {
        // Récupération des données de l'utilisateur authentifié via JWT
        $userData = auth()->user();

        // Vérification que l'utilisateur est bien authentifié
        if (!$userData) {
            return response()->json([
                "status" => false,
                "message" => "Utilisateur non authentifié"
            ], 401);
        }

        // Retourner les données du profil de l'utilisateur
        return response()->json([
            "status" => true,
            "message" => "Données du profil",
            "data" => [
                "id" => $userData->id,
                "nom" => $userData->nom,
                "prenom" => $userData->prenom,
                "email" => $userData->email,
                "dateNaissance" => $userData->dateNaissance,
                "telephone" => $userData->telephone,
                "sexe" => $userData->sexe,
                "photo_profil" => $userData->photo_profil,
                "adresse" => $userData->adresse,
                "roles" => $userData->getRoleNames()
            ]
        ]);
    }

}
