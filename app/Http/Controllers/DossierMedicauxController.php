<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDossierMedicauxRequest;
use App\Http\Requests\UpdateDossierMedicauxRequest;
use App\Models\DossierMedicaux;
use App\Models\Patient;
use App\Models\User;



class DossierMedicauxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dossiers = DossierMedicaux::with('patient')->get();
        return $this->customJsonResponse("Liste des dossiers médicaux", $dossiers);
    }

    // Méthode pour recupérer le dossier médical d'un patient spécifique
    public function getDossierByPatient($patient_id)
    {
        $dossier = DossierMedicaux::with('patient')
            ->where('patient_id', $patient_id)
            ->with('documents')
            ->first();

        if ($dossier) {
            return response()->json([
                'message' => 'Dossier médical récupéré avec succès',
                'data' => $dossier
            ], 200);
        } else {
            return response()->json([
                'message' => 'Aucun dossier médical trouvé pour ce patient',
            ], 404);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDossierMedicauxRequest $request)
    {
        $numeroDME = 'DME_' . date('Ymd_His') . '_' . str_pad(DossierMedical::count() + 1, 5, '0', STR_PAD_LEFT);

        $dossier = DossierMedicaux::create([
            'numero_dme' => $numeroDME,
            'date_creation' => now(),
            'date' => $request->date,
            'antecedents_medicaux' => $request->antecedents_medicaux,
            'traitements' => $request->traitements,
            'notes_observations' => $request->notes_observations,
            'intervention_chirurgicale' => $request->intervention_chirurgicale,
            'info_sup' => $request->info_sup,
            'patient_id' => $request->patient_id,
        ]);

        return response()->json([
            "message" => "Dossier médical créé avec succès",
            "data" => $dossier
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DossierMedicaux $dossierMedicaux)
    {
        // Charger les informations du patient ainsi que les documents associés
        $dossierMedicaux->load('patient', 'documents');

        return response()->json([
            'message' => 'Dossier médical récupéré avec succès',
            'data' => $dossierMedicaux
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDossierMedicauxRequest $request, DossierMedicaux $dossierMedicaux)
    {
        $dossierMedicaux->update($request->validated());

        return response()->json([
            "message" => "Dossier médical mis à jour avec succès",
            "data" => $dossierMedicaux
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DossierMedicaux $dossierMedicaux)
    {
        $dossierMedicaux->delete();
        return $this->customJsonResponse("Dossier médical supprimé avec succès", null, 200);
    }
}
