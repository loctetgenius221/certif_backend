<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\DossierMedicaux;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DossierMedicalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Dossiers médicaux pour le patient 1
         for ($i = 1; $i <= 5; $i++) {
            DossierMedicaux::create([
                'patient_id' => 1, // ID du premier patient
                'numero_dme' => Str::random(10), // Génère un numéro DME aléatoire
                'date_creation' => now()->subDays(rand(1, 30)), // Date de création dans le passé
                'antecedents_medicaux' => "Antécédents médicaux pour le patient 1, dossier $i",
                'traitements' => "Traitements pour le patient 1, dossier $i",
                'notes_observations' => "Notes et observations pour le patient 1, dossier $i",
                'intervention_chirurgicale' => "Intervention chirurgicale pour le patient 1, dossier $i",
                'info_sup' => "Informations supplémentaires pour le patient 1, dossier $i",
            ]);
        }

        // Dossiers médicaux pour le patient 2
        for ($i = 1; $i <= 5; $i++) {
            DossierMedicaux::create([
                'patient_id' => 2, // ID du deuxième patient
                'numero_dme' => Str::random(10), // Génère un numéro DME aléatoire
                'date_creation' => now()->subDays(rand(1, 30)), // Date de création dans le passé
                'antecedents_medicaux' => "Antécédents médicaux pour le patient 2, dossier $i",
                'traitements' => "Traitements pour le patient 2, dossier $i",
                'notes_observations' => "Notes et observations pour le patient 2, dossier $i",
                'intervention_chirurgicale' => "Intervention chirurgicale pour le patient 2, dossier $i",
                'info_sup' => "Informations supplémentaires pour le patient 2, dossier $i",
            ]);
        }
    }
}
