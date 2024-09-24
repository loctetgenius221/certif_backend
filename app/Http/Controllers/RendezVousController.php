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
        // Filtre pour s'assurer que les utilisateurs ont bien les rôles adéquats
        $rendezVous = $rendezVous->filter(function($rendezVous) {
            return $rendezVous->medecin->hasRole('medecin') && $rendezVous->patient->hasRole('patient');
        });
        return $this->customJsonResponse("Liste des rendez-vous", $rendezVous);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRendezVousRequest $request)
    {
        $rendezVous = new RendezVous();
        $rendezVous->created_by = auth()->id();
        $rendezVous->date = $request->date;
        $rendezVous->heure_debut = $request->heure_debut;
        $rendezVous->heure_fin = $request->heure_fin;
        $rendezVous->type_rendez_vous = $request->type_rendez_vous;
        $rendezVous->motif = $request->motif;
        $rendezVous->status = 'à venir';
        $rendezVous->lieu = $request->lieu;
        $rendezVous->medecin_id = $request->medecin_id;
        $rendezVous->patient_id = $request->patient_id;
        $rendezVous->save();

        return $this->customJsonResponse("Rendez-vous créé avec succès", $rendezVous);

    }

    /**
     * Display the specified resource.
     */
    public function show(RendezVous $rendezVous)
    {
        return $this->customJsonResponse("Rendez-vous récupéré avec succès", $rendezVous);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRendezVousRequest $request, RendezVous $rendezVous)
    {
        $rendezVous->fill($request->all());

        if ($request->has('lieu')) {
            $rendezVous->lieu = $request->lieu;
        }
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

        if (auth()->user()->hasRole('medecin') || auth()->user()->hasRole('administrateur')) {
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
        if (auth()->user()->hasRole('medecin') || auth()->user()->hasRole('administrateur')) {
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
    public function restore($id)
    {
        $rendezVous = RendezVous::withTrashed()->findOrFail($id);
        $rendezVous->restore();
        return $this->customJsonResponse("Rendez-vous restauré avec succès", $rendezVous);

    }
}
