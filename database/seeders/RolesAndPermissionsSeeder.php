<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Réinitialiser les caches des rôles et permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions pour la gestion des rendez-vous
        Permission::create(['name' => 'créer rendez-vous']);
        Permission::create(['name' => 'modifier rendez-vous']);
        Permission::create(['name' => 'annuler rendez-vous']);
        Permission::create(['name' => 'voir rendez-vous']);

        // Créer les permissions pour la gestion des dossiers médicaux
        Permission::create(['name' => 'créer dossier médical']);
        Permission::create(['name' => 'modifier dossier médical']);
        Permission::create(['name' => 'voir dossier médical']);
        Permission::create(['name' => 'supprimer dossier médical']);
        Permission::create(['name' => 'ajouter document au dossier médical']);

        // Créer les permissions pour la gestion des consultations
        Permission::create(['name' => 'planifier consultation']);
        Permission::create(['name' => 'modifier consultation']);
        Permission::create(['name' => 'annuler consultation']);
        Permission::create(['name' => 'voir consultation']);

        // Créer les permissions pour la gestion des utilisateurs
        Permission::create(['name' => 'créer utilisateur']);
        Permission::create(['name' => 'modifier utilisateur']);
        Permission::create(['name' => 'voir utilisateur']);
        Permission::create(['name' => 'supprimer utilisateur']);

        // Créer les permissions pour la gestion des rôles
        Permission::create(['name' => 'créer rôle']);
        Permission::create(['name' => 'assigner rôle']);
        Permission::create(['name' => 'modifier rôle']);
        Permission::create(['name' => 'voir rôle']);
        Permission::create(['name' => 'supprimer rôle']);

        // Créer les permissions pour la gestion des permissions
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

        // Créer les permissions pour la gestion des services
        Permission::create(['name' => 'créer service']);
        Permission::create(['name' => 'modifier service']);
        Permission::create(['name' => 'supprimer service']);
        Permission::create(['name' => 'voir service']);

        // Créer les rôles et leur assigner des permissions
        // Administrateur : gère tout le système
        $adminRole = Role::create(['name' => 'administrateur']);
        $adminRole->givePermissionTo([
            'créer rendez-vous', 'modifier rendez-vous', 'annuler rendez-vous', 'voir rendez-vous',
            'créer dossier médical', 'modifier dossier médical', 'voir dossier médical', 'supprimer dossier médical', 'ajouter document au dossier médical',
            'planifier consultation', 'modifier consultation', 'annuler consultation', 'voir consultation',
            'envoyer notification', 'supprimer notification', 'voir notification',
            'créer utilisateur', 'modifier utilisateur', 'voir utilisateur', 'supprimer utilisateur',
            'créer rôle', 'assigner rôle', 'modifier rôle', 'voir rôle', 'supprimer rôle',
            'créer permission', 'assigner permission', 'modifier permission', 'voir permission', 'supprimer permission',
            'créer service', 'modifier service', 'supprimer service', 'voir service'
        ]);

        // Médecin : peut consulter, créer et modifier des dossiers médicaux et gérer ses rendez-vous et consultations
        $medecinRole = Role::create(['name' => 'médecin']);
        $medecinRole->givePermissionTo([
            'créer rendez-vous', 'modifier rendez-vous', 'voir rendez-vous',
            'créer dossier médical', 'modifier dossier médical', 'voir dossier médical', 'ajouter document au dossier médical',
            'voir les documents', 'modifier un document', 'supprimer un document',
            'planifier consultation', 'modifier consultation', 'voir consultation',
            'voir notification',
        ]);

        // Assistant : aide à planifier des rendez-vous et voir les dossiers médicaux
        $assistantRole = Role::create(['name' => 'assistant']);
        $assistantRole->givePermissionTo([
            'créer rendez-vous', 'modifier rendez-vous', 'voir rendez-vous',
            'voir dossier médical',
            'planifier consultation', 'voir consultation',
            'voir notification',
            'voir les articles', 'ajouter un article', 'modifier un article', 'supprimer un article'
        ]);

        // Patient : peut voir son dossier médical et ses rendez-vous
        $patientRole = Role::create(['name' => 'patient']);
        $patientRole->givePermissionTo([
            'voir les rendez-vous', 'planifier un rendez-vous', 'annuler un rendez-vous',
            'voir les consultations', 'voir dossier médical',
            'voir les documents', 'voir les articles',
            'voir notification',
        ]);

    }
}
