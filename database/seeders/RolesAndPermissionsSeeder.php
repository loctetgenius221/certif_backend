<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Assistant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Désactiver la gestion du cache des permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {

            // Permissions pour la gestion des rendez-vous
            Permission::firstOrCreate(['name' => 'planifier rendez-vous']);
            Permission::firstOrCreate(['name' => 'modifier rendez-vous']);
            Permission::firstOrCreate(['name' => 'annuler rendez-vous']);
            Permission::firstOrCreate(['name' => 'voir rendez-vous']);

            // Permissions pour la gestion des dossiers médicaux
            Permission::firstOrCreate(['name' => 'créer dossier médical']);
            Permission::firstOrCreate(['name' => 'modifier dossier médical']);
            Permission::firstOrCreate(['name' => 'voir dossier médical']);
            Permission::firstOrCreate(['name' => 'supprimer dossier médical']);
            Permission::firstOrCreate(['name' => 'ajouter document au dossier médical']);

            // Permissions pour la gestion des consultations
            Permission::firstOrCreate(['name' => 'planifier consultation']);
            Permission::firstOrCreate(['name' => 'modifier consultation']);
            Permission::firstOrCreate(['name' => 'annuler consultation']);
            Permission::firstOrCreate(['name' => 'voir consultation']);

            // Permissions pour la gestion des utilisateurs
            Permission::firstOrCreate(['name' => 'créer utilisateur']);
            Permission::firstOrCreate(['name' => 'modifier utilisateur']);
            Permission::firstOrCreate(['name' => 'voir utilisateur']);
            Permission::firstOrCreate(['name' => 'supprimer utilisateur']);

            // Permissions pour la gestion des rôles
            Permission::firstOrCreate(['name' => 'créer rôle']);
            Permission::firstOrCreate(['name' => 'assigner rôle']);
            Permission::firstOrCreate(['name' => 'modifier rôle']);
            Permission::firstOrCreate(['name' => 'voir rôle']);
            Permission::firstOrCreate(['name' => 'supprimer rôle']);

            // Permissions pour la gestion des permissions
            Permission::firstOrCreate(['name' => 'créer permission']);
            Permission::firstOrCreate(['name' => 'assigner permission']);
            Permission::firstOrCreate(['name' => 'modifier permission']);
            Permission::firstOrCreate(['name' => 'voir permission']);
            Permission::firstOrCreate(['name' => 'supprimer permission']);

            // Permissions pour la gestion des documents
            Permission::firstOrCreate(['name' => 'voir les documents']);
            Permission::firstOrCreate(['name' => 'ajouter un document']);
            Permission::firstOrCreate(['name' => 'modifier un document']);
            Permission::firstOrCreate(['name' => 'supprimer un document']);

            // Permissions pour la gestion des articles
            Permission::firstOrCreate(['name' => 'voir les articles']);
            Permission::firstOrCreate(['name' => 'ajouter un article']);
            Permission::firstOrCreate(['name' => 'modifier un article']);
            Permission::firstOrCreate(['name' => 'supprimer un article']);

            // Permissions pour la gestion des services
            Permission::firstOrCreate(['name' => 'créer service']);
            Permission::firstOrCreate(['name' => 'modifier service']);
            Permission::firstOrCreate(['name' => 'supprimer service']);
            Permission::firstOrCreate(['name' => 'voir service']);

            Permission::firstOrCreate(['name' => 'supprimer notification']);
            Permission::firstOrCreate(['name' => 'voir notification']);

            // Créer les rôles et leur assigner des permissions
            $adminRole = Role::firstOrCreate(['name' => 'administrateur']);
            $adminRole->syncPermissions([
                'planifier rendez-vous', 'modifier rendez-vous', 'annuler rendez-vous', 'voir rendez-vous',
                'créer dossier médical', 'modifier dossier médical', 'voir dossier médical', 'supprimer dossier médical', 'ajouter document au dossier médical',
                'planifier consultation', 'modifier consultation', 'annuler consultation', 'voir consultation',
                'créer utilisateur', 'modifier utilisateur', 'voir utilisateur', 'supprimer utilisateur',
                'créer rôle', 'assigner rôle', 'modifier rôle', 'voir rôle', 'supprimer rôle',
                'créer permission', 'assigner permission', 'modifier permission', 'voir permission', 'supprimer permission',
                'créer service', 'modifier service', 'supprimer service', 'voir service',
                'supprimer notification', 'voir notification',
            ]);

            // Médecin
            $medecinRole = Role::firstOrCreate(['name' => 'medecin']);
            $medecinRole->syncPermissions([
                'planifier rendez-vous', 'modifier rendez-vous', 'voir rendez-vous',
                'créer dossier médical', 'modifier dossier médical', 'voir dossier médical', 'ajouter document au dossier médical',
                'voir les documents', 'modifier un document', 'supprimer un document',
                'planifier consultation', 'modifier consultation', 'voir consultation',
                'voir notification',
            ]);

            // Assistant
            $assistantRole = Role::firstOrCreate(['name' => 'assistant']);
            $assistantRole->syncPermissions([
                'planifier rendez-vous', 'modifier rendez-vous', 'voir rendez-vous',
                'voir dossier médical',
                'planifier consultation', 'voir consultation',
                'voir les articles', 'ajouter un article', 'modifier un article', 'supprimer un article',
                'voir notification',
            ]);

            // Patient
            $patientRole = Role::firstOrCreate(['name' => 'patient']);
            $patientRole->syncPermissions([
                'voir rendez-vous', 'planifier rendez-vous', 'annuler rendez-vous',
                'voir consultation', 'voir dossier médical',
                'voir les documents', 'voir les articles',
                'voir notification',
            ]);

            // Création des utilisateurs avec transactions
            $this->createUserWithRole('Sagna', 'Moussa', 'admin@example.com', 'adminpassword', 'administrateur');
            $this->createMedecin('Ndour', 'Fatou', 'medecin1@example.com', 'medecinpassword1', 'Cardiologie', 'MED001', 10, 'Hôpital de Fann');
            $this->createMedecin('Diop', 'Mamadou', 'medecin2@example.com', 'medecinpassword2', 'Pédiatrie', 'MED002', 12, 'Hôpital de Fann');
            $this->createAssistant('Faye', 'Marie', 'assistant1@example.com', 'assistantpassword1', 8, 'Hôpital de Fann');
            $this->createAssistant('Ba', 'Lamine', 'assistant2@example.com', 'assistantpassword2', 6, 'Hôpital de Fann');
            $this->createPatient('Fall', 'Awa', 'patient1@example.com', 'patientpassword1', 'PAT001', 'Louga', 'Louga', '770000006');
            $this->createPatient('Diallo', 'Cheikh', 'patient2@example.com', 'patientpassword2', 'PAT002', 'Tambacounda', 'Tambacounda', '770000007');
        });
    }

    private function createUserWithRole($nom, $prenom, $email, $password, $role)
    {
        $user = User::create([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => bcrypt($password),
            'dateNaissance' => '1990-01-01',
            'telephone' => '770000000',
            'sexe' => 'masculin',
            'adresse' => 'Dakar, Sénégal',
        ]);
        // Assurez-vous que l'utilisateur est bien créé avant d'assigner le rôle
        if ($user) {
            $user->assignRole($role);
        }
        return $user; // Retourner l'utilisateur créé
    }

    private function createMedecin($nom, $prenom, $email, $password, $specialite, $numeroLicence, $anneeExperience, $hopital)
    {
        $medecin = $this->createUserWithRole($nom, $prenom, $email, $password, 'medecin');
        if ($medecin) { // Vérifiez si le médecin a été créé
            Medecin::create([
                'user_id' => $medecin->id,
                'numeroLicence' => $numeroLicence,
                'annee_experience' => $anneeExperience,
                'hopital_affiliation' => $hopital,
            ]);
        }
    }

    private function createAssistant($nom, $prenom, $email, $password, $anneeExperience, $hopital)
    {
        $assistant = $this->createUserWithRole($nom, $prenom, $email, $password, 'assistant');
        if ($assistant) { // Vérifiez si l'assistant a été créé
            Assistant::create([
                'user_id' => $assistant->id,
                'annee_experience' => $anneeExperience,
                'hopital_affiliation' => $hopital,
            ]);
        }
    }

    private function createPatient($nom, $prenom, $email, $password, $numeroPatient, $ville, $region, $numero_urgence)
    {
        $patient = $this->createUserWithRole($nom, $prenom, $email, $password, 'patient');
        if ($patient) { // Vérifiez si le patient a été créé
            Patient::create([
                'user_id' => $patient->id,
                'numero_patient' => $numeroPatient,
                'ville' => $ville,
                'region' => $region,
                'numero_urgence' => $numero_urgence
            ]);
        }
    }
}
