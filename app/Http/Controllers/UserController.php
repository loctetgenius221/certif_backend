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
            $stats = [
                'total_users' => User::count(),
                'docteurrs' => User::role('médecin')->count(),
                'patients' => User::role('patient')->count(),
                'assistants' => User::role('assistant')->count(),
                // 'administrateurs' => User::role('administrateur')->count(),
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
