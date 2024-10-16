<?php

namespace App\Models;

use App\Models\Medecin;
use App\Models\RendezVous;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlageHoraire extends Model
{
    use HasFactory;
    protected $table = 'plages_horaires';
    protected $guarded = [];

    // Relation avec Medecin
    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    // Relation avec RendezVous
    public function rendezVous()
    {
        return $this->hasOne(RendezVous::class, 'plage_horaire_id');
    }

}
