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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rendez_vous_id');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('type_consultation');
            $table->text('diagnostic')->nullable();
            $table->text('notes_medecin')->nullable();
            $table->string('url_teleconsultation')->nullable();
            $table->timestamps();

            $table->foreign('rendez_vous_id')->references('id')->on('rendez_vous')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
