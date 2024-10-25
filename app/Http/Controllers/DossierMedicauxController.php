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
            // Décoder les champs JSON
            $dossier->antecedents_medicaux = json_decode($dossier->antecedents_medicaux);
            $dossier->traitements = json_decode($dossier->traitements);
            $dossier->notes_observations = json_decode($dossier->notes_observations);
            $dossier->intervention_chirurgicale = json_decode($dossier->intervention_chirurgicale);
            $dossier->info_sup = json_decode($dossier->info_sup);

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
        $numeroDME = 'DME_' . date('Ymd_His') . '_' . str_pad(DossierMedicaux::count() + 1, 5, '0', STR_PAD_LEFT);

        $dossier = DossierMedicaux::create([
            'numero_dme' => $numeroDME,
            'date_creation' => now(),
            'antecedents_medicaux' => json_encode($request->antecedents_medicaux),  // Encoder en JSON
            'traitements' => json_encode($request->traitements),  // Encoder en JSON
            'notes_observations' => json_encode($request->notes_observations),  // Encoder en JSON
            'intervention_chirurgicale' => json_encode($request->intervention_chirurgicale),  // Encoder en JSON
            'info_sup' => json_encode($request->info_sup),  // Encoder en JSON
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

    // Décoder les champs JSON uniquement si ce sont des chaînes
    $dossierMedicaux->antecedents_medicaux = is_string($dossierMedicaux->antecedents_medicaux) ? json_decode($dossierMedicaux->antecedents_medicaux) : $dossierMedicaux->antecedents_medicaux;
    $dossierMedicaux->traitements = is_string($dossierMedicaux->traitements) ? json_decode($dossierMedicaux->traitements) : $dossierMedicaux->traitements;
    $dossierMedicaux->notes_observations = is_string($dossierMedicaux->notes_observations) ? json_decode($dossierMedicaux->notes_observations) : $dossierMedicaux->notes_observations;
    $dossierMedicaux->intervention_chirurgicale = is_string($dossierMedicaux->intervention_chirurgicale) ? json_decode($dossierMedicaux->intervention_chirurgicale) : $dossierMedicaux->intervention_chirurgicale;
    $dossierMedicaux->info_sup = is_string($dossierMedicaux->info_sup) ? json_decode($dossierMedicaux->info_sup) : $dossierMedicaux->info_sup;

    return response()->json([
        'message' => 'Dossier médical récupéré avec succès',
        'data' => $dossierMedicaux
    ], 200);
}


    /**
     * Update the specified resource in storage.
     */
    // Backend (Laravel)
    public function update(UpdateDossierMedicauxRequest $request, DossierMedicaux $dossierMedicaux)
    {
        try {
            $dataToUpdate = [];

            // Helper function pour gérer la fusion des données JSON
            $mergeJsonData = function($existingData, $newData) {
                // Si les données existantes sont déjà un JSON valide
                if (is_string($existingData)) {
                    try {
                        $existingArray = json_decode($existingData, true) ?: [];
                        return is_array($existingArray) ? $existingArray : [$existingArray];
                    } catch (\Exception $e) {
                        return $existingData ? [$existingData] : [];
                    }
                }
                return $existingData ?: [];
            };

            // Gérer les antécédents médicaux
            if ($request->has('antecedents_medicaux')) {
                $dataToUpdate['antecedents_medicaux'] = $request->antecedents_medicaux;
            }

            // Gérer les traitements
            if ($request->has('traitements')) {
                $dataToUpdate['traitements'] = $request->traitements;
            }

            // Gérer les notes d'observations
            if ($request->has('notes_observations')) {
                $dataToUpdate['notes_observations'] = $request->notes_observations;
            }

            // Gérer les interventions chirurgicales
            if ($request->has('intervention_chirurgicale')) {
                $dataToUpdate['intervention_chirurgicale'] = $request->intervention_chirurgicale;
            }

            // Gérer les informations supplémentaires
            if ($request->has('info_sup')) {
                $dataToUpdate['info_sup'] = $request->info_sup;
            }

            // Mise à jour des données
            $dossierMedicaux->update($dataToUpdate);

            return response()->json([
                "success" => true,
                "message" => "Dossier médical mis à jour avec succès",
                "data" => $dossierMedicaux
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Erreur lors de la mise à jour du dossier médical",
                "error" => $e->getMessage()
            ], 500);
        }
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
