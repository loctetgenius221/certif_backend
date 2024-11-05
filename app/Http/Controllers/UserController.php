<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function getUserStatistics(): JsonResponse
    {
        try {

            $users = User::with(['roles.permissions', 'medecin', 'patient', 'assistant'])
            ->select('id', 'nom', 'prenom', 'email', 'adresse', 'telephone', 'sexe', 'dateNaissance', 'created_at', 'derniere_ligne_connexion', 'is_active')
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
                        'derniere_ligne_connexion' => $user->derniere_ligne_connexion,
                        'is_active' => $user-> is_active,
                        'roles_and_permissions' => $rolesWithPermissions,
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

    public function getRolePermissions(): JsonResponse {
          // Récupérer tous les rôles
          $roles = Role::with('permissions')->get();
          $permissions = Permission::all();

          // Préparer les données à retourner
          $rolesAndPermissions = $roles->map(function ($role) {
              return [
                  'id' => $role->id,
                  'role_name' => $role->name,
                  'permissions' => $role->permissions->pluck('name'), // Récupère juste les noms des permissions
              ];
          });
          $allPermissions = $permissions;

          // Retourner la réponse JSON
          return response()->json([
              'roles_and_permissions' => $rolesAndPermissions,
              'All_permissions' => $allPermissions
          ]);
    }

    // Méthode pour bloquer un utilisateur
    public function blockUser($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Vérifiez si l'utilisateur est déjà bloqué
            if (!$user->is_active) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cet utilisateur est déjà bloqué.'
                ], 400);
            }

            // Bloquez l'utilisateur en mettant à jour le champ is_active
            $user->is_active = false;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Utilisateur bloqué avec succès.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du blocage de l\'utilisateur.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Méthode pour débloquer un utilisateur
    public function unblockUser($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Vérifiez si l'utilisateur est déjà actif
            if ($user->is_active) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cet utilisateur est déjà actif.'
                ], 400);
            }

            // Débloquez l'utilisateur en mettant à jour le champ is_active
            $user->is_active = true;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Utilisateur débloqué avec succès.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du déblocage de l\'utilisateur.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

     // Liste des rôles
     public function getRole()
     {
         $roles = Role::all();
         return response()->json($roles);
     }

     // Ajouter un rôle
     public function storeRole(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'name' => 'required|string|max:255|unique:roles,name',
             'permissions' => 'array',
             'permissions.*' => 'string|exists:permissions,name',
         ]);

         if ($validator->fails()) {
             return response()->json($validator->errors(), 422);
         }

         $role = Role::create(['name' => $request->name]);

         // Assigner les permissions au rôle
         if ($request->permissions) {
             $role->givePermissionTo($request->permissions);
         }

         return response()->json($role, 201);
     }

     // Modifier un rôle
     public function updateRole(Request $request, $id)
     {
         $role = Role::findOrFail($id);

         // Empêcher la modification des rôles de base
         if (in_array($role->name, ['admin', 'médecin', 'patient', 'assistant'])) {
             return response()->json(['error' => 'Modification de ce rôle non autorisée'], 403);
         }

         $validator = Validator::make($request->all(), [
             'name' => 'required|string|max:255|unique:roles,name,' . $id,
             'permissions' => 'array',
             'permissions.*' => 'string|exists:permissions,name',
         ]);

         if ($validator->fails()) {
             return response()->json($validator->errors(), 422);
         }

         $role->name = $request->name;
         $role->save();

         // Mettre à jour les permissions
         $role->syncPermissions($request->permissions);

         return response()->json($role);
     }

     // Supprimer un rôle
     public function destroyRole($id)
     {
         $role = Role::findOrFail($id);

         // Empêcher la suppression des rôles de base
         if (in_array($role->name, ['admin', 'médecin', 'patient', 'assistant'])) {
             return response()->json(['error' => 'Suppression de ce rôle non autorisée'], 403);
         }

         $role->delete();

         return response()->json("Role supprimer avec succès", 204);
     }
}
