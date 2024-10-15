<?php

namespace App\Models;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medecin extends User
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Relation avec PlagesHoraires
    public function plagesHoraires()
    {
        return $this->hasMany(PlageHoraire::class);
    }

    // Relation indirecte avec RendezVous à travers PlageHoraire
    public function rendezVous()
    {
        return $this->hasManyThrough(RendezVous::class, PlageHoraire::class, 'medecin_id', 'plage_horaire_id');
    }

}
