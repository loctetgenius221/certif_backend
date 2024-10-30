<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    public function getUserStatistics(): JsonResponse
    {
        try {

            $users = User::with(['roles.permissions', 'medecin', 'patient', 'assistant'])
            ->select('id', 'nom', 'prenom', 'email', 'adresse', 'telephone', 'sexe', 'dateNaissance', 'created_at')
            ->get();

            $stats = [
                'total_users' => User::count(),
                'docteurs' => User::role('medecin')->count(),
                'patients' => User::role('patient')->count(),
                'assistants' => User::role('assistant')->count(),
                'administrateurs' => User::role('administrateur')->count(),
                'users' => $users->map(function ($user) {
                     // Transformer les rôles et permissions en format plus lisible
                    $rolesWithPermissions = $user->roles->map(function ($role) {
                        return [
                            'name' => $role->name,
                            'permissions' => $role->permissions->pluck('name')->toArray()
                        ];
                    })->toArray();

                    return [
                        'id' => $user->id,
                        'nom' => $user->nom,
                        'prenom' => $user->prenom,
                        'date_inscription' => $user->created_at,
                        'email' => $user->email,
                        'adresse' => $user->adresse,
                        'sexe' => $user->sexe,
                        'dateNaissance' => $user->dateNaissance,
                        'telephone' => $user->telephone,
                        'roles_and_permissions' => $rolesWithPermissions, // Nouveau format avec permissions
                        'medecin' => $user->medecin?->toArray(),
                        'patient' => $user->patient?->toArray(),
                        'assistant' => $user->assistant?->toArray(),
                        'created_at' => $user->created_at,
                    ];
                })
            ];

            return response()->json([
                'status' => 'success',
                'data' => $stats
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur est survenue lors de la récupération des statistiques des utilisateurs.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
