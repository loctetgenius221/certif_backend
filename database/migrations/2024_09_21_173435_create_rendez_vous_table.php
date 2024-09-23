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
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('created_by');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->enum('type_rendez_vous', ['présentiel', 'téléconsultation']);
            $table->enum('motif', ['consultation', 'suivi']);
            $table->enum('status', ['en_attente', 'confirmé', 'annulé']);
            $table->string('lieu')->nullable();
            $table->timestamps();

            $table->foreignId('medecin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};
