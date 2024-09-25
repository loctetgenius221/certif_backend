<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Article;
use App\Models\Service;
use App\Models\RendezVous;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Assistant;
use App\Models\DossierMedicaux;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
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

    // Get the identifier that will be stored in the subject claim of the JWT.
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }


    // Relation 1:1 avec Medecin
    public function medecin()
    {
        return $this->hasOne(Medecin::class);
    }

    // Relation 1:1 avec Patient
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    // Relation 1:1 avec Assistant
    public function assistant()
    {
        return $this->hasOne(Assistant::class);
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
