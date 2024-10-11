<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'nom' => 'Cardiologie',
                'description' => 'Service de diagnostic et de traitement des maladies du cœur et des vaisseaux sanguins.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Neurologie',
                'description' => 'Service qui traite les maladies du système nerveux, incluant le cerveau, la moelle épinière, et les nerfs.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Pneumologie',
                'description' => 'Service spécialisé dans le diagnostic et le traitement des maladies pulmonaires.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Orthopédie',
                'description' => 'Service de soins pour les affections du système musculo-squelettique (os, muscles, articulations).',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Médecine interne',
                'description' => 'Service de médecine générale pour adultes, prenant en charge diverses pathologies complexes.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Chirurgie générale',
                'description' => 'Service chirurgical prenant en charge diverses interventions comme la chirurgie digestive et endocrinienne.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Pédiatrie',
                'description' => 'Service médical spécialisé dans les soins aux enfants.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Radiologie',
                'description' => 'Service d\'imagerie médicale permettant le diagnostic par rayons X, IRM, scanner, etc.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Gynécologie-Obstétrique',
                'description' => 'Service prenant en charge la santé reproductive, les grossesses et les accouchements.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Dermatologie',
                'description' => 'Service traitant les maladies de la peau, des ongles, et des cheveux.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Psychiatrie',
                'description' => 'Service de soins pour les troubles mentaux et psychiatriques.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Ophtalmologie',
                'description' => 'Service de soins pour les troubles de la vision et les maladies des yeux.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'ORL (Oto-Rhino-Laryngologie)',
                'description' => 'Service spécialisé dans les maladies des oreilles, du nez et de la gorge.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Urologie',
                'description' => 'Service traitant les maladies des voies urinaires et du système reproducteur masculin.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Anesthésie-Réanimation',
                'description' => 'Service de gestion des anesthésies pendant les opérations et de soins intensifs pour les patients critiques.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
            [
                'nom' => 'Endocrinologie',
                'description' => 'Service traitant les maladies hormonales et métaboliques comme le diabète.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'assistant_id' => null,
            ],
        ]);

    }
}
