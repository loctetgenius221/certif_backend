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



    public function getRendezVousByPatient($patientId)
    {
        $user = auth()->user();

        if ($user->hasRole('patient') && $user->patient->id != $patientId) {
            return response()->json([
                'message' => 'Accès non autorisé. Vous ne pouvez voir que vos propres rendez-vous.'
            ], 403);
        }

        // Récupérer les rendez-vous avec les relations
        $rendezVous = RendezVous::with(['medecin.user', 'patient.user', 'createdBy'])
            ->where('patient_id', $patientId)
            ->get();

        // Formatter les données pour inclure les informations utilisateur
        $rendezVousFormatted = $rendezVous->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'heure_debut' => $item->heure_debut,
                'heure_fin' => $item->heure_fin,
                'type_rendez_vous' => $item->type_rendez_vous,
                'motif' => $item->motif,
                'status' => $item->status,
                'lieu' => $item->lieu,
                'medecin' => [
                    'id' => $item->medecin->user->id,
                    'nom' => $item->medecin->user->nom,
                    'prenom' => $item->medecin->user->prenom,
                    'photo' => $item->medecin->user->photo_profil,
                ],
                'patient' => [
                    'id' => $item->patient->user->id,
                    'nom' => $item->patient->user->nom,
                    'prenom' => $item->patient->user->prenom,
                    'photo' => $item->patient->user->photo_profil,
                ],
                'created_by' => [
                    'id' => $item->createdBy->id,
                    'nom' => $item->createdBy->nom,
                    'prenom' => $item->createdBy->prenom,
                ],
            ];
        });

        return response()->json([
            'message' => 'Liste des rendez-vous pour le patient',
            'data' => $rendezVousFormatted
        ]);
    }


    // Recupérer les rendez-vous d'un medecin
    public function getRendezVousByMedecin($medecinId)
    {
        $user = auth()->user();

        // Vérifier si l'utilisateur a le rôle de médecin et s'il correspond au médecin demandé
        if ($user->hasRole('medecin') && $user->medecin->id != $medecinId) {
            return response()->json([
                'message' => 'Accès non autorisé. Vous ne pouvez voir que vos propres rendez-vous.'
            ], 403);
        }

        // Récupérer les rendez-vous du médecin avec les relations
        $rendezVous = RendezVous::with(['medecin.user', 'patient.user', 'createdBy'])
            ->where('medecin_id', $medecinId)
            ->get();

        // Formatter les données pour inclure les informations utilisateur
        $rendezVousFormatted = $rendezVous->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'heure_debut' => $item->heure_debut,
                'heure_fin' => $item->heure_fin,
                'type_rendez_vous' => $item->type_rendez_vous,
                'motif' => $item->motif,
                'status' => $item->status,
                'lieu' => $item->lieu,
                'medecin' => [
                    'id' => $item->medecin->user->id,
                    'nom' => $item->medecin->user->nom,
                    'prenom' => $item->medecin->user->prenom,
                    'photo' => $item->medecin->user->photo_profil,
                ],
                'patient' => [
                    'id' => $item->patient->user->id,
                    'nom' => $item->patient->user->nom,
                    'prenom' => $item->patient->user->prenom,
                    'photo' => $item->patient->user->photo_profil,
                ],
                'created_by' => [
                    'id' => $item->createdBy->id,
                    'nom' => $item->createdBy->nom,
                    'prenom' => $item->createdBy->prenom,
                ],
            ];
        });

        return response()->json([
            'message' => 'Liste des rendez-vous pour le médecin',
            'data' => $rendezVousFormatted
        ]);
    }



}
