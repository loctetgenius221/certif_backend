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
            $table->unsignedBigInteger('patient_id'); // Associe un patient
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });

        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->unsignedBigInteger('medecin_id'); // Associe un médecin
            $table->unsignedBigInteger('patient_id'); // Associe un patient

            $table->foreign('medecin_id')->references('id')->on('medecins')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('auteur_id');
            $table->foreign('auteur_id')->references('id')->on('assistants')->onDelete('cascade');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->unsignedBigInteger('assistant_id')->nullable(); // Associe un médecin

            $table->foreign('assistant_id')->references('id')->on('assistants')->onDelete('set null');
        });

        Schema::table('medecins', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable(); // Associe un médecin
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les clés étrangères si la migration est annulée
        Schema::table('dossier_medicaux', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
        });

        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->dropForeign(['medecin_id']);
            $table->dropForeign(['patient_id']);
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['auteur_id']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['assistant_id']);
        });
    }
};
