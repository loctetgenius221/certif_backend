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
        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean('lu')->default(false);  // Colonne 'lu' pour marquer si la notification est lue
            $table->softDeletes();  // Colonne 'deleted_at' pour le soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('lu');  // Supprime la colonne 'lu'
            $table->dropSoftDeletes();
        });
    }
};
