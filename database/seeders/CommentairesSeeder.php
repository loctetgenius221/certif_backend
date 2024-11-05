<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentairesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('comments')->insert([
            [
                'article_id' => 1, // ID d'un article existant
                'auteur' => 'Jean Dupont',
                'commentaire' => 'Excellent article, très bien rédigé !',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'article_id' => 2, // ID d'un autre article existant
                'auteur' => 'Marie Martin',
                'commentaire' => 'Merci pour cet article informatif.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'article_id' => 1, // Vous pouvez ajouter plusieurs commentaires pour le même article
                'auteur' => 'Aliou Diop',
                'commentaire' => 'J\'ai trouvé cet article très utile.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Ajoutez d'autres commentaires si nécessaire
        ]);
    }
}
