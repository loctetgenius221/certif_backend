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
        Schema::table('users', function (Blueprint $table) {
            $table->date('dateNaissance')->nullable()->change();
            $table->enum('sexe', ['masculin', 'féminin'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('dateNaissance')->nullable(false)->change();
            $table->enum('sexe', ['masculin', 'féminin'])->nullable(false)->change();
        });
    }
};
