<?php

namespace App\Models;

use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consultations extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function rendezVous()
    {
        return $this->belongsTo(RendezVous::class, 'rendez_vous_id');
    }

      /**
     * Relation avec le médecin via RendezVous
     */
    public function medecin()
    {
        return $this->hasOneThrough(
            Medecin::class,
            RendezVous::class,
            'id', // Clé étrangère sur rendez_vous
            'id', // Clé primaire sur medecin
            'rendez_vous_id', // Clé étrangère sur consultations
            'medecin_id' // Clé étrangère sur rendez_vous
        );
    }

    /**
     * Relation avec le patient via RendezVous
     */
    public function patient()
    {
        return $this->hasOneThrough(
            Patient::class,
            RendezVous::class,
            'id', // Clé étrangère sur rendez_vous
            'id', // Clé primaire sur patient
            'rendez_vous_id', // Clé étrangère sur consultations
            'patient_id' // Clé étrangère sur rendez_vous
        );
    }
}
