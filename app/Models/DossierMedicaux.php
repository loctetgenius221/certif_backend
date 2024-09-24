<?php

namespace App\Models;

use App\Models\User;
use App\Models\Documents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DossierMedicaux extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation 1:N avec Document
    public function documents()
    {
        return $this->hasMany(Documents::class);
    }
}
