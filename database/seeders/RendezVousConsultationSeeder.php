<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RendezVousConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insérer des rendez-vous
        DB::table('rendez_vous')->insert([
            [
                'created_by' => 1, // ID de l'utilisateur qui a créé le rendez-vous
                'date' => Carbon::create('2024', '10', '10'), // Date du premier rendez-vous
                'heure_debut' => '10:00:00',
                'heure_fin' => '11:00:00',
                'type_rendez_vous' => 'présentiel',
                'motif' => 'consultation',
                'status' => 'à venir',
                'lieu' => 'Hôpital Principal',
                'medecin_id' => 1, // ID du médecin
                'patient_id' => 1, // ID du patient
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'created_by' => 1,
                'date' => Carbon::create('2024', '10', '12'), // Date du deuxième rendez-vous
                'heure_debut' => '14:00:00',
                'heure_fin' => '15:00:00',
                'type_rendez_vous' => 'téléconsultation',
                'motif' => 'suivi',
                'status' => 'à venir',
                'lieu' => 'En ligne',
                'medecin_id' => 2,
                'patient_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Obtenir les IDs des rendez-vous créés pour les consultations
        $rendezVous1Id = DB::getPdo()->lastInsertId();
        $rendezVous2Id = $rendezVous1Id + 1;

        // Insérer des consultations
        DB::table('consultations')->insert([
            [
                'rendez_vous_id' => $rendezVous1Id, // Référence au premier rendez-vous
                'date' => Carbon::create('2024', '10', '10'),
                'heure_debut' => '10:00:00',
                'heure_fin' => '11:00:00',
                'type_consultation' => 'Consultation en cabinet',
                'diagnostic' => 'Fatigue générale',
                'notes_medecin' => 'Suivi recommandé pour vérifier l\'état général du patient.',
                'url_teleconsultation' => null, // Pas de lien pour une téléconsultation en présentiel
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rendez_vous_id' => $rendezVous2Id, // Référence au deuxième rendez-vous
                'date' => Carbon::create('2024', '10', '12'),
                'heure_debut' => '14:00:00',
                'heure_fin' => '15:00:00',
                'type_consultation' => 'Téléconsultation',
                'diagnostic' => 'Suivi post-opératoire',
                'notes_medecin' => 'Rétablissement post-opératoire satisfaisant.',
                'url_teleconsultation' => 'https://teleconsultation.example.com/room/abc123',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
