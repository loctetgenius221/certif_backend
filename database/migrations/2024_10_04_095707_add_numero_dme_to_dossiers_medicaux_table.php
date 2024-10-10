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
            $table->string('numero_dme')->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dossier_medicaux', function (Blueprint $table) {
            $table->dropColumn('numero_dme');
        });
    }
};
