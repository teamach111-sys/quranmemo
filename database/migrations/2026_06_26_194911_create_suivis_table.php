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
            $table->date('createdate');
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');;
            $table->foreignId('groupe_id')->constrained('groupes')->onDelete('cascade');;
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');;
            $table->boolean('isArchived');
            $table->text('observation')->nullable();
            $table->foreignId('sourate_id')->constrained('sourates');
            $table->foreignId('annee_scolaire_id')->constrained('annee_scolaires')->onDelete('cascade');;
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
