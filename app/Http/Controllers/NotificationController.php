<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;  // Utilisez Auth pour récupérer l'utilisateur
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;

class NotificationController extends Controller
{
     // Récupérer les notifications de l'utilisateur connecté
     public function getNotifications()
     {
         $user = Auth::user();  // Récupère l'utilisateur authentifié

         // Récupérer les notifications non supprimées de l'utilisateur
         $notifications = Notification::where('destinataire_id', $user->id)
             ->whereNull('deleted_at')  // Assure que la notification n'est pas supprimée
             ->get();

         return response()->json([
             'status' => true,
             'data' => $notifications
         ]);
        //  return $this->customJsonResponse("La liste de notifications.", $notification);

     }

     // Marquer une notification comme lue
     public function markAsRead($notificationId)
     {
         $notification = Notification::find($notificationId);

         if ($notification) {
             // Marquer la notification comme lue
             $notification->update(['lu' => true]);

             return response()->json([
                 'status' => true,
                 'message' => 'Notification marquée comme lue.'
             ]);
         }

         return response()->json([
             'status' => false,
             'message' => 'Notification non trouvée.'
         ], 404);

     }

     // Supprimer une notification en soft delete
     public function deleteNotification($notificationId)
     {
         $notification = Notification::find($notificationId);

         if ($notification) {
             // Suppression en soft delete de la notification
             $notification->delete();

             return response()->json([
                 'status' => true,
                 'message' => 'Notification supprimée avec succès.'
             ]);
         }

         return response()->json([
             'status' => false,
             'message' => 'Notification non trouvée.'
         ], 404);
     }
}
