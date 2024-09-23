<?php

namespace App\Models;

use App\Models\User;
use App\Models\Consultations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RendezVous extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relation N:1 avec InfoMedecin
    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    // Relation N:1 avec User (patient)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Relation 1:1 avec Consultation
    public function consultation()
    {
        return $this->hasOne(Consultations::class);
    }

    // Relation inverse avec User (créateur du rendez-vous)
    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'destinataire_id');
    }

}
