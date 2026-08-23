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
        Schema::create('etudiants', function(Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('annee_scolaire_id')->constrained('annee_scolaires');
            $table->foreignId('groupe_id')->constrained('groupes');
            $table->foreignId('promotion_id')->constrained('promotions');
            $table->string('prenom');
            $table->string('photo')->nullable();
            $table->string('sexe');
            $table->date('date_naissance');
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();
            $table->string('parent_nom')->nullable();
            $table->string('parent_telephone')->nullable();
            $table->string('parent_relation')->nullable();
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
