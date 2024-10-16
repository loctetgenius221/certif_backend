<?php

namespace App\Http\Controllers;

use App\Models\PlageHoraire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlageHoraireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer toutes les plages horaires
        $plagesHoraires = PlageHoraire::with('medecin.user')->get();
        return response()->json($plagesHoraires);
    }

    public function getPlageByMedecin($id, Request $request) {
        // Récupérer la date de la requête
        $date = $request->query('date');

        // Filtrer les plages horaires par médecin et par date, si les paramètres sont fournis
        $query = PlageHoraire::with('medecin.user')
            ->where('medecin_id', $id); // Utiliser l'ID du médecin directement

        if ($date) {
            $query->where('date', $date);
        }

        $plagesHoraires = $query->get();

        return response()->json($plagesHoraires);
    }


    public function updateStatus(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'status' => 'required|in:disponible,occupé', // Assurez-vous que le statut est valide
        ]);

        $plageHoraire = PlageHoraire::find($id);

        $plageHoraire->status = $request->status;
        $plageHoraire->save();

        return response()->json(['message' => 'Statut mis à jour avec succès', 'plage_horaire' => $plageHoraire]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données d'entrée
        $validator = Validator::make($request->all(), [
            'medecin_id' => 'required|exists:medecins,id',
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'recurrence' => 'required|in:unique,quotidienne,hebdomadaire',
            'status' => 'required|in:disponible,occupé',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Création de la plage horaire
        $plageHoraire = PlageHoraire::create($request->all());
        return response()->json($plageHoraire, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PlageHoraire $plageHoraire)
    {
        // Afficher une plage horaire spécifique avec le médecin et son utilisateur
        $plageHoraire->load('medecin.user');
        return response()->json($plageHoraire);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlageHoraire $plageHoraire)
    {
        // Validation des données d'entrée
        $validator = Validator::make($request->all(), [
            'medecin_id' => 'sometimes|exists:medecins,id',
            'date' => 'sometimes|date',
            'heure_debut' => 'sometimes|date_format:H:i',
            'heure_fin' => 'sometimes|date_format:H:i|after:heure_debut',
            'recurrence' => 'sometimes|in:unique,quotidienne,hebdomadaire',
            'status' => 'sometimes|in:disponible,occupé',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Mise à jour de la plage horaire
        $plageHoraire->update($request->all());
        return response()->json($plageHoraire);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlageHoraire $plageHoraire)
    {
        // Suppression de la plage horaire
        $plageHoraire->delete();
        return response()->json(['message' => 'Plage horaire supprimée avec succès']);
    }
}
