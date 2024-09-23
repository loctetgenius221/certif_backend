<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Article;
use App\Models\Service;
use App\Models\RendezVous;
use App\Models\InfoMedecin;
use App\Models\InfoPatient;
use App\Models\InfoAssistant;
use App\Models\DossierMedicaux;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relation 1:1 avec InfoMedecin
    public function infoMedecin()
    {
        return $this->hasOne(InfoMedecin::class);
    }

    // Relation 1:1 avec InfoPatient
    public function infoPatient()
    {
        return $this->hasOne(InfoPatient::class);
    }

    // Relation 1:1 avec InfoAssistant
    public function infoAssistant()
    {
        return $this->hasOne(InfoAssistant::class);
    }

    public function service()
    {
        return $this->hasOne(Service::class);
    }

    // Relation 1:N avec RendezVous
    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class, 'created_by');
    }

    // Relation 1:1 avec DossierMedical
    public function dossierMedical()
    {
        return $this->hasOne(DossierMedicaux::class);
    }

    // Relation 1:N avec Articles (en tant qu'auteur)
    public function articles()
    {
        return $this->hasMany(Article::class, 'auteur_id');
    }
}
