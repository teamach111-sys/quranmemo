<?php

declare(strict_types=1);

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
        Schema::create('suivis', function(Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->boolean('isArchived')->default(false);
            $table->text('observation')->nullable();
            $table->foreignId('sourate_id')->constrained('sourates');
            $table->foreignId('annee_scolaire_id')->constrained('annee_scolaires')->onDelete('cascade');
            $table->string('etat_de_recitation');
            $table->index(['etudiant_id', 'date']);
            $table->index(['isArchived']);
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
