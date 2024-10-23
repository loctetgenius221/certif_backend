<?php

namespace Database\Seeders;

use App\Models\DossierMedicaux;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DossierMedicauxJsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Générer le numéro DME pour le patient 1
        $numeroDME1 = 'DME_' . date('Ymd_His') . '_' . str_pad(DossierMedicaux::count() + 1, 5, '0', STR_PAD_LEFT);

        // Dossier médical pour le patient 1
        DossierMedicaux::create([
            'numero_dme' => $numeroDME1,
            'date_creation' => now(),
            'antecedents_medicaux' => json_encode([
                "Diabète",
                "Hypertension"
            ]),
            'traitements' => json_encode([
                [
                    "id" => 1,
                    "nom" => "Amoxicilline",
                    "dosage" => "500mg",
                    "date_debut" => "2024-01-15",
                    "date_fin" => "2024-01-22",
                    "prescripteur" => "Dr Martin"
                ],
                [
                    "id" => 2,
                    "nom" => "Ibuprofène",
                    "dosage" => "400mg",
                    "date_debut" => "2024-02-01",
                    "date_fin" => "2024-02-07",
                    "prescripteur" => "Dr Durant"
                ]
            ]),
            'notes_observations' => json_encode([
                "Patient montre des signes de guérison rapide."
            ]),
            'intervention_chirurgicale' => json_encode([
                "Appendicectomie - 2022"
            ]),
            'info_sup' => json_encode([
                "Allergie au latex"
            ]),
            'patient_id' => 1,
        ]);

        // Générer le numéro DME pour le patient 2
        $numeroDME2 = 'DME_' . date('Ymd_His') . '_' . str_pad(DossierMedicaux::count() + 1, 5, '0', STR_PAD_LEFT);

        // Dossier médical pour le patient 2
        DossierMedicaux::create([
            'numero_dme' => $numeroDME2,
            'date_creation' => now(),
            'antecedents_medicaux' => json_encode([
                "Asthme",
                "Problèmes cardiaques"
            ]),
            'traitements' => json_encode([
                [
                    "id" => 1,
                    "nom" => "Salbutamol",
                    "dosage" => "100mcg",
                    "date_debut" => "2024-03-01",
                    "date_fin" => "2024-03-15",
                    "prescripteur" => "Dr Ndiaye"
                ],
                [
                    "id" => 2,
                    "nom" => "Aspirine",
                    "dosage" => "75mg",
                    "date_debut" => "2024-03-05",
                    "date_fin" => "2024-03-12",
                    "prescripteur" => "Dr Diop"
                ]
            ]),
            'notes_observations' => json_encode([
                "Le patient a des difficultés respiratoires."
            ]),
            'intervention_chirurgicale' => json_encode([
                "Pontage coronarien - 2021"
            ]),
            'info_sup' => json_encode([
                "Fumeur depuis 10 ans"
            ]),
            'patient_id' => 2,
        ]);
    }
}
