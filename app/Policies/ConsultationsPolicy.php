<?php

namespace App\Policies;

use App\Models\Consultations;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConsultationsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Consultations $consultations): bool
    {
        // Autorise seulement le médecin ou le patient associé au rendez-vous de la consultation
        return $user->id === $consultations->rendezVous->medecin->user_id ||
               $user->id === $consultations->rendezVous->patient->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Consultations $consultations): bool
    {
        // Seul le médecin peut mettre à jour la consultation
        return $user->id === $consultations->rendezVous->medecin->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Consultations $consultations): bool
    {
         // Seul le médecin peut supprimer la consultation
         return $user->id === $consultations->rendezVous->medecin->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Consultations $consultations): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Consultations $consultations): bool
    {
        //
    }
}
