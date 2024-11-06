<?php

namespace App\Models;

use App\Models\Media;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Assistant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function auteur()
    // {
    //     return $this->belongsTo(Assistant::class);
    // }

    public function categorie()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relation avec le modèle Media
    public function medias()
    {
        return $this->hasMany(Media::class);
    }
}
