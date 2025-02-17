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
        Schema::create('info_assistants', function (Blueprint $table) {
            $table->id();
            $table->integer('annee_experience');
            $table->string('hopital_affiliation');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relation avec user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_assistants');
    }
};
