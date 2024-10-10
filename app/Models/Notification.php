<?php

namespace App\Models;

use App\Models\User;
use App\Models\RendezVous;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function rendez_vous()
    {
        return $this->belongsTo(RendezVous::class);
    }

    public function destinataire()
    {
        return $this->belongsTo(User::class, 'destinataire_id');
    }

}
