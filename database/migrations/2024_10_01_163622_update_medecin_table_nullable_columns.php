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
        Schema::table('medecins', function (Blueprint $table) {
            $table->string('numeroLicence')->nullable()->change();
            $table->integer('annee_experience')->nullable()->change();
            $table->string('hopital_affiliation')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medecins', function (Blueprint $table) {
            $table->string('numeroLicence')->nullable(false)->change();
            $table->integer('annee_experience')->nullable(false)->change();
            $table->string('hopital_affiliation')->nullable(false)->change();
        });
    }
};
