<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['nom' => 'Médecine Générale', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Pédiatrie', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Cardiologie', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Dermatologie', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Chirurgie', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('categories')->insert($categories);
    }
}
