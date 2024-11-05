<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'titre' => 'Importance de la vaccination chez les enfants',
                'contenu' => 'La vaccination est essentielle pour prévenir les maladies chez les enfants...',
                'date_publication' => now(),
                'image' => 'vaccination.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'auteur_id' => 1, // Assurez-vous que cet ID existe dans votre table utilisateurs
                'categorie_id' => 2, // Pédiatrie
            ],
            [
                'titre' => 'Les bienfaits d\'une alimentation équilibrée',
                'contenu' => 'Une alimentation équilibrée est cruciale pour maintenir une bonne santé...',
                'date_publication' => now(),
                'image' => 'alimentation.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'auteur_id' => 1,
                'categorie_id' => 3, // Médecine Générale
            ],
            [
                'titre' => 'Prévention des maladies cardiovasculaires',
                'contenu' => 'La prévention des maladies cardiovasculaires passe par un mode de vie sain...',
                'date_publication' => now(),
                'image' => 'cardio.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'auteur_id' => 1,
                'categorie_id' => 3, // Cardiologie
            ],
            [
                'titre' => 'Comprendre les allergies cutanées',
                'contenu' => 'Les allergies cutanées peuvent provoquer des démangeaisons et des éruptions cutanées...',
                'date_publication' => now(),
                'image' => 'allergies.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'auteur_id' => 1,
                'categorie_id' => 4, // Dermatologie
            ],
            [
                'titre' => 'Les avancées en chirurgie robotique',
                'contenu' => 'La chirurgie robotique a révolutionné le domaine de la chirurgie...',
                'date_publication' => now(),
                'image' => 'chirurgie_robotique.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'auteur_id' => 1,
                'categorie_id' => 5, // Chirurgie
            ],
        ];

        DB::table('articles')->insert($articles);
    }
}
