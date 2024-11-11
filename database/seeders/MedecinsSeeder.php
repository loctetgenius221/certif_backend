<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Medecin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MedecinsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Liste des médecins avec des noms sénégalais
        $medecins = [
            ['nom' => 'Fall', 'prenom' => 'Mansour', 'numeroLicence' => 'MED003', 'annee_experience' => 10],
            ['nom' => 'Diallo', 'prenom' => 'Aminata', 'numeroLicence' => 'MED004', 'annee_experience' => 8],
            ['nom' => 'Fall', 'prenom' => 'Awa', 'numeroLicence' => 'MED005', 'annee_experience' => 12],
            ['nom' => 'Seck', 'prenom' => 'Oumar', 'numeroLicence' => 'MED006', 'annee_experience' => 7],
            ['nom' => 'Sarr', 'prenom' => 'Mariama', 'numeroLicence' => 'MED007', 'annee_experience' => 9],
            ['nom' => 'Ba', 'prenom' => 'Ibrahima', 'numeroLicence' => 'MED008', 'annee_experience' => 15],
            ['nom' => 'Dieng', 'prenom' => 'Khady', 'numeroLicence' => 'MED009', 'annee_experience' => 11],
            ['nom' => 'Thiam', 'prenom' => 'Abdoulaye', 'numeroLicence' => 'MED010', 'annee_experience' => 13],
            ['nom' => 'Gueye', 'prenom' => 'Aissatou', 'numeroLicence' => 'MED011', 'annee_experience' => 6],
            ['nom' => 'Faye', 'prenom' => 'Cheikh', 'numeroLicence' => 'MED012', 'annee_experience' => 14],
            ['nom' => 'Ka', 'prenom' => 'Binta', 'numeroLicence' => 'MED013', 'annee_experience' => 8],
            ['nom' => 'Kane', 'prenom' => 'Adama', 'numeroLicence' => 'MED014', 'annee_experience' => 10],
            ['nom' => 'Mbaye', 'prenom' => 'Modou', 'numeroLicence' => 'MED015', 'annee_experience' => 12],
            ['nom' => 'Sow', 'prenom' => 'Astou', 'numeroLicence' => 'MED016', 'annee_experience' => 9],
            ['nom' => 'Diagne', 'prenom' => 'Moussa', 'numeroLicence' => 'MED017', 'annee_experience' => 7],
            ['nom' => 'Ndiaye', 'prenom' => 'Aminata', 'numeroLicence' => 'MED018', 'annee_experience' => 11],
            ['nom' => 'Sy', 'prenom' => 'Demba', 'numeroLicence' => 'MED019', 'annee_experience' => 6]
        ];

        // Vérifie que les services existent en base de données
        $serviceIds = DB::table('services')->pluck('id')->all();

        if (empty($serviceIds)) {
            // Si aucun service n'est trouvé, afficher une erreur et arrêter le seeder
            throw new \Exception("La table 'services' ne contient aucun enregistrement. Exécutez le seeder des services d'abord.");
        }

        foreach ($medecins as $medecin) {
            // Créer un utilisateur pour chaque médecin
            $user = User::create([
                'nom' => $medecin['nom'],
                'prenom' => $medecin['prenom'],
                'email' => strtolower($medecin['prenom'] . '.' . $medecin['nom'] . '@fannconsult.sn'),
                'password' => Hash::make('password'),
                'dateNaissance' => now()->subYears(30),
                'telephone' => '77' . rand(1000000, 9999999),
                'sexe' => 'masculin',
                'adresse' => 'Dakar, Sénégal',
                'photo_profil' => 'default.png',
            ]);

            // Assigner le médecin à un service aléatoire
            Medecin::create([
                'numeroLicence' => $medecin['numeroLicence'],
                'annee_experience' => $medecin['annee_experience'],
                'hopital_affiliation' => 'Hôpital Fann',
                'user_id' => $user->id,
                'service_id' => $serviceIds[array_rand($serviceIds)], // Sélectionne un ID de service aléatoire
            ]);
        }
    }
}
