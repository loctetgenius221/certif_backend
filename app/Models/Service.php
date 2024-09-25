<?php

namespace App\Models;

use App\Models\User;
use App\Models\Assistant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function assistant()
    {
        return $this->belongsTo(Assistant::class);
    }
}
