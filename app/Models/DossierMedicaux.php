<?php

namespace App\Models;

use App\Models\Patient;
use App\Models\Documents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DossierMedicaux extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'dossiers_medicaux';

    // Relation avec l'utilisateur (le patient)
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relation 1:N avec Document
    public function documents()
    {
        return $this->hasMany(Documents::class);
    }
}
