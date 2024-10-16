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
        Schema::create('plages_horaires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medecin_id');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->enum('recurrence', ['unique', 'quotidienne', 'hebdomadaire'])->default('unique');
            $table->enum('status', ['disponible', 'occupé'])->default('disponible');
            $table->timestamps();

            $table->foreign('medecin_id')->references('id')->on('medecins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plages_horaires');
    }
};
