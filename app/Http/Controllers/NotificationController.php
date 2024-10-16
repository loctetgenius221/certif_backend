<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::where('destinataire_id', $userId)->get();
        return response()->json($notifications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotificationRequest $request)
    {
        // Logique pour créer un rendez-vous
        $rendezVous = RendezVous::create([
            'created_by' => auth()->user()->id,
            'date' => $request->date,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'type_rendez_vous' => $request->type_rendez_vous,
            'motif' => $request->motif,
            'status' => 'à venir',
            'lieu' => $request->lieu,
        ]);

        // Générer une notification
        $notification = Notification::create([
            'contenu' => 'Un nouveau rendez-vous a été réservé.',
            'date_envoi' => now(),
            'destinataire_id' => $request->destinataire_id,  // Médecin ou assistant
            'rendez_vous_id' => $rendezVous->id,
        ]);

        return response()->json(['message' => 'Rendez-vous créé et notification envoyée !']);
    }


    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationRequest $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
