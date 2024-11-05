<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\MedecinsSeeder;
use Database\Seeders\ServicesTableSeeder;
use Database\Seeders\DossierMedicalSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\RendezVousConsultationSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RolesAndPermissionsSeeder::class,
            RendezVousConsultationSeeder::class,
            DossierMedicalSeeder::class,
            ServicesTableSeeder::class,
            MedecinsSeeder::class,
            CategoriesTableSeeder::class,
            ArticlesTableSeeder::class,
        ]);
    }
}
