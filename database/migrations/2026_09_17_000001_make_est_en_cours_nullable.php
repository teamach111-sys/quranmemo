<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Allow years to be created without being marked as the current year.
     */
    public function up(): void
    {
        Schema::table('annee_scolaires', function (Blueprint $table) {
            $table->boolean('est_en_cours')->nullable()->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annee_scolaires', function (Blueprint $table) {
            $table->boolean('est_en_cours')->default(false)->change();
        });
    }
};