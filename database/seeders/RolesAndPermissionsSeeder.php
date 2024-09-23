<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\InfoMedecin;
use App\Models\InfoPatient;
use App\Models\InfoAssistant;
use Illuminate\Database\Seeder;
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
        // Créer ou vérifier les permissions pour la gestion des rendez-vous
        Permission::create(['name' => 'planifier rendez-vous']);
        Permission::create(['name' => 'modifier rendez-vous']);
        Permission::create(['name' => 'annuler rendez-vous']);
        Permission::create(['name' => 'voir rendez-vous']);

        // Créer ou vérifier les permissions pour la gestion des dossiers médicaux
        Permission::create(['name' => 'créer dossier médical']);
        Permission::create(['name' => 'modifier dossier médical']);
        Permission::create(['name' => 'voir dossier médical']);
        Permission::create(['name' => 'supprimer dossier médical']);
        Permission::create(['name' => 'ajouter document au dossier médical']);

        // Créer ou vérifier les permissions pour la gestion des consultations
        Permission::create(['name' => 'planifier consultation']);
        Permission::create(['name' => 'modifier consultation']);
        Permission::create(['name' => 'annuler consultation']);
        Permission::create(['name' => 'voir consultation']);

        // Créer ou vérifier les permissions pour la gestion des utilisateurs
        Permission::create(['name' => 'créer utilisateur']);
        Permission::create(['name' => 'modifier utilisateur']);
        Permission::create(['name' => 'voir utilisateur']);
        Permission::create(['name' => 'supprimer utilisateur']);

        // Créer ou vérifier les permissions pour la gestion des rôles
        Permission::create(['name' => 'créer rôle']);
        Permission::create(['name' => 'assigner rôle']);
        Permission::create(['name' => 'modifier rôle']);
        Permission::create(['name' => 'voir rôle']);
        Permission::create(['name' => 'supprimer rôle']);

        // Créer ou vérifier les permissions pour la gestion des permissions
        Permission::create(['name' => 'créer permission']);
        Permission::create(['name' => 'assigner permission']);
        Permission::create(['name' => 'modifier permission']);
        Permission::create(['name' => 'voir permission']);
        Permission::create(['name' => 'supprimer permission']);

        // Permissions pour la gestion des documents
        Permission::create(['name' => 'voir les documents']);
        Permission::create(['name' => 'ajouter un document']);
        Permission::create(['name' => 'modifier un document']);
        Permission::create(['name' => 'supprimer un document']);

        // Permissions pour la gestion des articles
        Permission::create(['name' => 'voir les articles']);
        Permission::create(['name' => 'ajouter un article']);
        Permission::create(['name' => 'modifier un article']);
        Permission::create(['name' => 'supprimer un article']);

        // Créer ou vérifier les permissions pour la gestion des services
        Permission::create(['name' => 'créer service']);
        Permission::create(['name' => 'modifier service']);
        Permission::create(['name' => 'supprimer service']);
        Permission::create(['name' => 'voir service']);

        Permission::create(['name' => 'supprimer notification']);
        Permission::create(['name' => 'voir notification']);

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

        // Médecin : peut consulter, créer et modifier des dossiers médicaux et gérer ses rendez-vous et consultations
        $medecinRole = Role::firstOrCreate(['name' => 'médecin']);
        $medecinRole->syncPermissions([
            'planifier rendez-vous', 'modifier rendez-vous', 'voir rendez-vous',
            'créer dossier médical', 'modifier dossier médical', 'voir dossier médical', 'ajouter document au dossier médical',
            'voir les documents', 'modifier un document', 'supprimer un document',
            'planifier consultation', 'modifier consultation', 'voir consultation',
            'voir notification',
        ]);

        // Assistant : aide à planifier des rendez-vous et voir les dossiers médicaux
        $assistantRole = Role::firstOrCreate(['name' => 'assistant']);
        $assistantRole->syncPermissions([
            'planifier rendez-vous', 'modifier rendez-vous', 'voir rendez-vous',
            'voir dossier médical',
            'planifier consultation', 'voir consultation',
            'voir les articles', 'ajouter un article', 'modifier un article', 'supprimer un article',
            'voir notification',
        ]);

        // Patient : peut voir son dossier médical et ses rendez-vous
        $patientRole = Role::firstOrCreate(['name' => 'patient']);
        $patientRole->syncPermissions([
            'voir rendez-vous', 'planifier rendez-vous', 'annuler rendez-vous',
            'voir consultation', 'voir dossier médical',
            'voir les documents', 'voir les articles',
            'voir notification',
        ]);

         // Création de l'utilisateur administrateur
         $admin = User::create([
            'nom' => 'Sagna',
            'prenom' => 'Moussa',
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpassword'),
            'dateNaissance' => '1990-01-01',
            'telephone' => '770000000',
            'sexe' => 'masculin',
            'adresse' => 'Dakar, Sénégal'
        ]);
        $admin->assignRole($adminRole);

        // Création des utilisateurs médecins
        $medecin1 = User::create([
            'nom' => 'Ndour',
            'prenom' => 'Fatou',
            'email' => 'medecin1@example.com',
            'password' => bcrypt('medecinpassword1'),
            'dateNaissance' => '1985-05-10',
            'telephone' => '770000001',
            'sexe' => 'féminin',
            'adresse' => 'Saint-Louis, Sénégal'
        ]);
        $medecin1->assignRole($medecinRole);

        InfoMedecin::create([
            'user_id' => $medecin1->id,
            'specialite' => 'Cardiologie',
            'numeroLicence' => 'MED001',
            'annee_experience' => 10,
            'hopital_affiliation' => 'Hôpital de Fann'
        ]);

        $medecin2 = User::create([
            'nom' => 'Diop',
            'prenom' => 'Mamadou',
            'email' => 'medecin2@example.com',
            'password' => bcrypt('medecinpassword2'),
            'dateNaissance' => '1980-03-20',
            'telephone' => '770000002',
            'sexe' => 'masculin',
            'adresse' => 'Thiès, Sénégal'
        ]);
        $medecin2->assignRole($medecinRole);

        InfoMedecin::create([
            'user_id' => $medecin2->id,
            'specialite' => 'Pédiatrie',
            'numeroLicence' => 'MED002',
            'annee_experience' => 12,
            'hopital_affiliation' => 'Hôpital de Fann'
        ]);

        // Création des utilisateurs assistants
        $assistant1 = User::create([
            'nom' => 'Faye',
            'prenom' => 'Marie',
            'email' => 'assistant1@example.com',
            'password' => bcrypt('assistantpassword1'),
            'dateNaissance' => '1992-11-15',
            'telephone' => '770000003',
            'sexe' => 'féminin',
            'adresse' => 'Kaolack, Sénégal'
        ]);
        $assistant1->assignRole($assistantRole);

        InfoAssistant::create([
            'user_id' => $assistant1->id,
            'annee_experience' => 8,
            'hopital_affiliation' => 'Hôpital de Fann'
        ]);

        $assistant2 = User::create([
            'nom' => 'Ba',
            'prenom' => 'Lamine',
            'email' => 'assistant2@example.com',
            'password' => bcrypt('assistantpassword2'),
            'dateNaissance' => '1994-08-05',
            'telephone' => '770000004',
            'sexe' => 'masculin',
            'adresse' => 'Ziguinchor, Sénégal'
        ]);
        $assistant2->assignRole($assistantRole);

        InfoAssistant::create([
            'user_id' => $assistant2->id,
            'annee_experience' => 6,
            'hopital_affiliation' => 'Hôpital de Fann'
        ]);

        // Création des utilisateurs patients
        $patient1 = User::create([
            'nom' => 'Fall',
            'prenom' => 'Awa',
            'email' => 'patient1@example.com',
            'password' => bcrypt('patientpassword1'),
            'dateNaissance' => '1996-07-01',
            'telephone' => '770000005',
            'sexe' => 'féminin',
            'adresse' => 'Louga, Sénégal'
        ]);
        $patient1->assignRole($patientRole);

        InfoPatient::create([
            'user_id' => $patient1->id,
            'numero_patient' => 'PAT001',
            'ville' => 'Louga',
            'region' => 'Louga',
            'numero_urgence' => '770000006'
        ]);

        $patient2 = User::create([
            'nom' => 'Diallo',
            'prenom' => 'Cheikh',
            'email' => 'patient2@example.com',
            'password' => bcrypt('patientpassword2'),
            'dateNaissance' => '1999-09-12',
            'telephone' => '770000006',
            'sexe' => 'masculin',
            'adresse' => 'Tambacounda, Sénégal'
        ]);
        $patient2->assignRole($patientRole);

        InfoPatient::create([
            'user_id' => $patient2->id,
            'numero_patient' => 'PAT002',
            'ville' => 'Tambacounda',
            'region' => 'Tambacounda',
            'numero_urgence' => '770000007'
        ]);

    }
}
