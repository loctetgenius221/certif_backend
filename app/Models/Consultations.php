<?php

namespace App\Models;

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
}
