<?php

namespace App\Models;

use App\Models\Assistant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function auteur()
    {
        return $this->belongsTo(Assistant::class);
    }
}
