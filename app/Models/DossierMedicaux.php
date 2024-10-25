<?php

namespace App\Models;

use App\Models\Patient;
use App\Models\Documents;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DossierMedicaux extends Model
{
    use HasFactory;

    protected $casts = [
        'antecedents_medicaux' => 'array',
        'traitements' => 'array',
        'notes_observations' => 'array',
        'intervention_chirurgicale' => 'array',
        'info_sup' => 'array',
    ];

    protected $table = 'dossier_medicaux';

    protected $guarded = [];

    // Relation avec l'utilisateur (le patient)
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id')->with(['user']);
    }

    // Relation 1:N avec Document
    public function documents()
    {
        return $this->hasMany(Documents::class, 'dossier_medical_id');
    }
}
