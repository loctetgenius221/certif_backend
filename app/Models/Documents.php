<?php

namespace App\Models;

use App\Models\DossierMedicaux;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documents extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dossierMedical()
    {
        return $this->belongsTo(DossierMedicaux::class);
    }
}
