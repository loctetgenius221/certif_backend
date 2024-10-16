<?php

namespace App\Models;

use App\Models\User;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Consultations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\PlageHoraire;

class RendezVous extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rendez_vous';

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    // Relation N:1 avec InfoMedecin
    public function medecin()
    {
        return $this->belongsTo(Medecin::class, 'medecin_id')->with('user');
    }

    // Relation N:1 avec User (patient)
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id')->with('user');
    }

    // Relation 1:1 avec Consultation
    public function consultation()
    {
        return $this->hasOne(Consultations::class);
    }

    // Relation inverse avec User (créateur du rendez-vous)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'destinataire_id');
    }

    // Relation avec PlageHoraire
    public function plageHoraire()
    {
        return $this->belongsTo(PlageHoraire::class, 'plage_horaire_id');
    }
}
