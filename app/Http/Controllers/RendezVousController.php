<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Medecin;
use App\Models\RendezVous;
use App\Models\Consultations;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreRendezVousRequest;
use App\Http\Requests\UpdateRendezVousRequest;
use App\Services\JitsiTeleconsultationService;
use App\Notifications\NewAppointmentNotification;
use App\Notifications\ConfirmationRendezVousNotification;


class RendezVousController extends Controller
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
        $rendezVous = RendezVous::with(['medecin.service', 'patient', 'createdBy'])->get();
        return $this->customJsonResponse("Liste des rendez-vous", $rendezVous);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRendezVousRequest $request)
    {
        // Récupérer l'utilisateur connecté
        $user = auth()->user();

        try {
            DB::beginTransaction();

            // Créer un nouvel objet RendezVous
            $rendezVous = new RendezVous();
            $rendezVous->created_by = $user->id;
            $rendezVous->date = $request->date;
            $rendezVous->plage_horaire_id = $request->plage_horaire_id;
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

            // Créer la consultation associée
            $consultation = new Consultations();
            $consultation->rendez_vous_id = $rendezVous->id;
            $consultation->date = $rendezVous->date;
            $consultation->heure_debut = $rendezVous->heure_debut;
            $consultation->heure_fin = $rendezVous->heure_fin;
            $consultation->type_consultation = $request->type_rendez_vous; // Même type que le rendez-vous

            // Si la consultation est en ligne, générer l'URL Jitsi
            if ($request->type_rendez_vous === 'téléconsultation') {
                $roomName = 'consultation-' . $rendezVous->id . '-' . now()->timestamp;
                $consultation->url_teleconsultation = $this->jitsiService->createJitsiRoom($roomName);
            }

            // Sauvegarder la consultation
            $consultation->save();

            // Ajouter la consultation à la réponse
            $rendezVous->consultation = $consultation;

            // Récupérer le médecin
            $medecin = User::find(Medecin::where('id', $rendezVous->medecin_id)->value('user_id'));

            // Création de la notification pour le médecin
            if ($medecin) {
                DB::table('notifications')->insert([
                    'contenu' => sprintf(
                        'Un nouveau rendez-vous a été programmé avec %s %s le %s à %s.',
                        $user->nom,
                        $user->prenom,
                        Carbon::parse($rendezVous->date)->format('d/m/Y'),
                        $rendezVous->heure_debut
                    ),
                    'date_envoi' => Carbon::now(),
                    'destinataire_id' => $medecin->id,
                    'rendez_vous_id' => $rendezVous->id,
                    'lu' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Envoi d'une notification par email au médecin
            if ($medecin) {
                $medecin->notify(new NewAppointmentNotification($rendezVous));
            }

            // Notification pour le patient
            $patient = $rendezVous->patient->user; // Récupérer l'utilisateur associé au patient

            if ($patient) {
                // Notification par email et enregistrement dans la base de données
                $patient->notify(new ConfirmationRendezVousNotification($rendezVous));

                // Insertion manuelle dans la table `notifications` (optionnel, si besoin)
                DB::table('notifications')->insert([
                    'contenu' => sprintf(
                        'Votre rendez-vous pour le %s à %s est confirmé.',
                        \Carbon\Carbon::parse($rendezVous->date)->format('d/m/Y'),
                        $rendezVous->heure_debut
                    ),
                    'date_envoi' => now(),
                    'destinataire_id' => $patient->id,
                    'rendez_vous_id' => $rendezVous->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'lu' => false,
                ]);
            }


            DB::commit();

            return $this->customJsonResponse("Rendez-vous et consultation créés avec succès", $rendezVous);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Une erreur est survenue lors de la création du rendez-vous et de la consultation.',
                'error' => $e->getMessage()
            ], 500);
        }
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
