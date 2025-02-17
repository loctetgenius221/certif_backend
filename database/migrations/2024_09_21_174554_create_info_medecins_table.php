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
        Schema::create('info_medecins', function (Blueprint $table) {
            $table->id();
            $table->string('specialite');
            $table->string('numeroLicence');
            $table->integer('annee_experience');
            $table->string('hopital_affiliation');
            $table->string('langues_parlees')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Relation avec user

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_medecins');
    }
};
