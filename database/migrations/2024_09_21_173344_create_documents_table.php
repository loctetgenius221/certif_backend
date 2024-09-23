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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dossier_medical_id');
            $table->string('type_document');
            $table->string('file_path');
            $table->date('upload_date');
            $table->unsignedBigInteger('upload_by');
            $table->timestamps();

            $table->foreign('dossier_medical_id')->references('id')->on('dossier_medicaux')->onDelete('cascade');
            $table->foreign('upload_by')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
