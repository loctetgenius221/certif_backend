<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\Consultations;
use App\Http\Requests\StoreConsultationsRequest;
use App\Http\Requests\UpdateConsultationsRequest;

class ConsultationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consultations = Consultations::all();
        return $this->customJsonResponse("Liste des consultations récupérée avec succès", $consultations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConsultationsRequest $request)
    {
        // Vérifiez si le rendez-vous existe
        $rendezVous = RendezVous::find($request->rendez_vous_id);
        if (!$rendezVous) {
            return response()->json([
                "message" => "Rendez-vous non trouvé."
            ], 404);
        }

        $consultation = new Consultations();
        $consultation->rendez_vous_id = $request->rendez_vous_id;
        $consultation->date = $request->date;
        $consultation->heure_debut = $request->heure_debut;
        $consultation->heure_fin = $request->heure_fin;
        $consultation->type_consultation = $request->type_consultation;
        $consultation->diagnostic = $request->diagnostic;
        $consultation->notes_medecin = $request->notes_medecin;
        $consultation->url_teleconsultation = $request->url_teleconsultation;

        $consultation->save();

        return response()->json([
            "message" => "Consultation créée avec succès.",
            "data" => $consultation
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Consultations $consultation)
    {
        return $this->customJsonResponse("Consultation récupérée avec succès", $consultation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConsultationsRequest $request, Consultations $consultation)
    {
        $consultation->fill($request->validated());
        $consultation->save();

        return response()->json([
            "message" => "Consultation mise à jour avec succès.",
            "data" => $consultation
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consultations $consultation)
    {
        $consultation->delete();
        return $this->customJsonResponse("Consultation supprimée avec succès.", null, 200);
    }
}
