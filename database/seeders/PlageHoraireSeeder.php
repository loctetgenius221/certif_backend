<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\PlageHoraire;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlageHoraireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Liste des médecins avec lesquels nous allons créer les plages horaires
        $medecins = [
            ['medecin_id' => 1, 'date' => '2024-10-16'],
            ['medecin_id' => 2, 'date' => '2024-10-16'],
            ['medecin_id' => 3, 'date' => '2024-10-17'],
            ['medecin_id' => 4, 'date' => '2024-10-17'],
            ['medecin_id' => 5, 'date' => '2024-10-17'],
            ['medecin_id' => 6, 'date' => '2024-10-16'],
            ['medecin_id' => 7, 'date' => '2024-10-17'],
            ['medecin_id' => 8, 'date' => '2024-10-17'],
            ['medecin_id' => 9, 'date' => '2024-10-16'],
            ['medecin_id' => 10, 'date' => '2024-10-17'],
            ['medecin_id' => 1, 'date' => '2024-10-17'],
        ];

        // Définir les heures de début et de fin pour les créneaux
        $heureDebut = Carbon::createFromTime(8, 0);  // 08h00
        $heureFin = Carbon::createFromTime(12, 0);   // 12h00

        // Boucle pour chaque médecin
        foreach ($medecins as $medecin) {
            $currentTime = $heureDebut->copy();

            // Boucle pour chaque créneau horaire entre 08h00 et 12h00
            while ($currentTime->lessThan($heureFin)) {
                $nextTime = $currentTime->copy()->addMinutes(30);  // Ajouter 30 minutes pour chaque créneau

                PlageHoraire::create([
                    'medecin_id' => $medecin['medecin_id'],
                    'date' => $medecin['date'],
                    'heure_debut' => $currentTime->toTimeString(),   // Heure de début
                    'heure_fin' => $nextTime->toTimeString(),        // Heure de fin
                    'recurrence' => 'unique',
                    'status' => 'disponible',
                ]);

                // Passer au créneau suivant (30 minutes plus tard)
                $currentTime = $nextTime;
            }
        }
    }
}
