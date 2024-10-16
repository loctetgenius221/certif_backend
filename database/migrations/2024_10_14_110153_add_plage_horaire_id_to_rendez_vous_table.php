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
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->unsignedBigInteger('plage_horaire_id')->nullable()->after('id');
            $table->foreign('plage_horaire_id')->references('id')->on('plages_horaires')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->unsignedBigInteger('plage_horaire_id');
            $table->foreign('plage_horaire_id')->references('id')->on('plages_horaires')->onDelete('cascade');
        });
    }
};
