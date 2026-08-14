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
        Schema::create('suivis', function (Blueprint $table) {
            $table->id();
            $table->date('createdat');
            $table->foreignId('promotion_id')->constrained('promotions');
            $table->foreign('groupe_id')->constrained('groupes');
            $table->foreign('etudiant_id')->constrained('etudiants');
            $table->boolean('isArchived');
            $table->text('observation')->nullable();
            $table->foreignId('sourate_id')->constrained('sourates');
            $table->foreignId('annee_scolaire_id')->constrained('annee_scolaires');
            $table->string('etat_de_recitation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivis');
    }
};
