<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dossier_medicaux', function (Blueprint $table) {
            // Convertir les colonnes en longtext temporairement pour préparer la conversion en JSON
            $table->longText('antecedents_medicaux')->nullable()->change();
            $table->longText('traitements')->nullable()->change();
            $table->longText('notes_observations')->nullable()->change();
            $table->longText('intervention_chirurgicale')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dossier_medicaux', function (Blueprint $table) {
            // Revenir au type text original si besoin
            $table->text('antecedents_medicaux')->nullable()->change();
            $table->text('traitements')->nullable()->change();
            $table->text('notes_observations')->nullable()->change();
            $table->text('intervention_chirurgicale')->nullable()->change();
        });
    }
};
