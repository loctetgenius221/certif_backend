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
        return $this->belongsTo(RendezVous::class);
    }

    // Relation avec le médecin via RendezVous
    public function medecin()
    {
        return $this->rendezVous->medecin();
    }

    // Relation avec le patient via RendezVous
    public function patient()
    {
        return $this->rendezVous->patient();
    }
}
