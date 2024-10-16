<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\Consultations;
use App\Http\Requests\StoreConsultationsRequest;
use App\Http\Requests\UpdateConsultationsRequest;
use App\Services\JitsiTeleconsultationService;


class ConsultationsController extends Controller
{

    protected $jitsiService;

    // Injection du service Jitsi via le constructeur
    public function __construct(JitsiTeleconsultationService $jitsiService)
    {
        $this->jitsiService = $jitsiService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer toutes les consultations avec les relations nécessaires
        $consultations = Consultations::with('rendezVous.medecin', 'rendezVous.patient')->get();
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

        // Créer la consultation
        $consultation = new Consultations();
        $consultation->rendez_vous_id = $request->rendez_vous_id;
        $consultation->date = $request->date;
        $consultation->heure_debut = $request->heure_debut;
        $consultation->heure_fin = $request->heure_fin;
        $consultation->type_consultation = $request->type_consultation;
        $consultation->diagnostic = $request->diagnostic;
        $consultation->notes_medecin = $request->notes_medecin;

        // Si la consultation est "en ligne", générer une URL pour la salle de téléconsultation
        if ($request->type_consultation === 'en ligne') {
            // Générer un nom de salle unique
            $roomName = 'consultation-' . $rendezVous->id . '-' . now()->timestamp;

            // Utiliser le service Jitsi pour créer l'URL de la salle
            $consultation->url_teleconsultation = $this->jitsiService->createJitsiRoom($roomName);
        }

        // Sauvegarder la consultation
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
        // Charger les relations du rendez-vous, médecin, et patient
        $consultation->load('rendezVous.medecin.user', 'rendezVous.patient.user');
        return $this->customJsonResponse("Consultation récupérée avec succès", $consultation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConsultationsRequest $request, Consultations $consultation)
    {
        // Mettre à jour les informations de la consultation
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
        // Supprimer la consultation
        $consultation->delete();
        return $this->customJsonResponse("Consultation supprimée avec succès.", null, 200);
    }
}
