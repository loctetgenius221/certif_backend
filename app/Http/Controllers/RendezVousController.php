<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRendezVousRequest;
use App\Http\Requests\UpdateRendezVousRequest;
use App\Models\RendezVous;

class RendezVousController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rendezVous = RendezVous::with(['medecin', 'patient', 'createdBy'])->get();
        return $this->customJsonResponse("Liste des rendez-vous", $rendezVous);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRendezVousRequest $request)
    {
        // Récupérer l'utilisateur connecté
        $user = auth()->user();

        // Créer un nouvel objet RendezVous
        $rendezVous = new RendezVous();
        $rendezVous->created_by = $user->id; // ID de l'utilisateur qui crée le rendez-vous
        $rendezVous->date = $request->date;
        $rendezVous->heure_debut = $request->heure_debut;
        $rendezVous->heure_fin = $request->heure_fin;
        $rendezVous->type_rendez_vous = $request->type_rendez_vous;
        $rendezVous->motif = $request->motif;
        $rendezVous->status = 'à venir';
        $rendezVous->lieu = $request->lieu;
        $rendezVous->medecin_id = $request->medecin_id;

        // Vérifier le rôle de l'utilisateur connecté
        if ($user->hasRole('assistant')) {
            $rendezVous->patient_id = $request->patient_id;
        } elseif ($user->hasRole('patient')) {
            $rendezVous->patient_id = $user->patient->id;
        } else {
            return response()->json([
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        // Enregistrer le rendez-vous
        $rendezVous->save();

        return $this->customJsonResponse("Rendez-vous créé avec succès", $rendezVous);
    }

    /**
     * Display the specified resource.
     */
    public function show(RendezVous $rendezVous)
    {
        $rendezVous->load(['medecin', 'patient', 'createdBy']);
        return $this->customJsonResponse("Rendez-vous récupéré avec succès", $rendezVous);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRendezVousRequest $request, RendezVous $rendezVous)
    {

        // Récupérer l'utilisateur connecté
        $user = auth()->user();
        $rendezVous->fill($request->all());

        if ($request->has('lieu')) {
            $rendezVous->lieu = $request->lieu;
        }

        if ($request->has('medecin_id')) {
            $rendezVous->medecin_id = $request->medecin_id; // Assurez-vous que ce champ est bien validé dans la requête
        }

        // Vérifier le rôle de l'utilisateur connecté
        if ($user->hasRole('assistant')) {
            // L'assistant peut sélectionner un autre patient
            $rendezVous->patient_id = $request->patient_id; // ID du patient sélectionné
        } elseif ($user->hasRole('patient')) {
            // Le patient utilise son propre ID
            $rendezVous->patient_id = $user->patient->id; // Assurez-vous que la relation patient est définie dans User
        } else {
            return response()->json([
                'message' => 'Accès non autorisé.'
            ], 403);
        }

        // Enregistrer les changements
        $rendezVous->update();

        return $this->customJsonResponse("Rendez-vous mis à jour avec succès", $rendezVous);

    }

    /**
     * Changer le statut du rendez-vous (Annulé, Terminé).
     */
    public function changeStatus(Request $request, RendezVous $rendezVous)
    {
        $request->validate([
            'status' => 'required|in:à venir,en cours,terminé,annulé'
        ]);

        if (auth()->user()->hasRole('médecin') || auth()->user()->hasRole('administrateur')) {
            if ($rendezVous->status !== 'terminé') {
                $rendezVous->status = $request->status;
                $rendezVous->update();

                return response()->json([
                    "message" => "Statut du rendez-vous mis à jour avec succès",
                    "data" => $rendezVous
                ]);
            }

            return response()->json([
                "message" => "Impossible de modifier un rendez-vous terminé"
            ], 403);
        }

        return response()->json([
            "message" => "Action non autorisée"
        ], 403);

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(RendezVous $rendezVous)
    {
        if (auth()->user()->hasRole('médecin') || auth()->user()->hasRole('administrateur')) {
            $rendezVous->delete();
            return response()->json([
                "message" => "Rendez-vous supprimé avec succès"
            ]);
        }

        return response()->json([
            "message" => "Action non autorisée"
        ], 403);
    }

    // Pour restaurer un rendez-vous supprimé
    public function restore($rendezVous)
    {
        $rendezVous = RendezVous::withTrashed()->findOrFail($rendezVous);
        $rendezVous->restore();
        return $this->customJsonResponse("Rendez-vous restauré avec succès", $rendezVous);

    }
}
