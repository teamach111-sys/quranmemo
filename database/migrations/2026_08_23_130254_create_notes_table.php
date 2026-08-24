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
        Schema::create('notes', function(Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained('matieres');
            $table->foreignId('promotion_id')
                ->constrained('promotions')
                ->onDelete('cascade');
            $table->foreignId('etudiant_id')
                ->constrained('etudiants')
                ->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periodes');
            $table->integer('note')->unsigned()->min(0)->max(20);
            $table->string('observation')->nullable();
            $table->boolean('isArchived')->default(false);
            $table->unique(['promotion_id', 'etudiant_id', 'periode_id']);
            $table->index(['promotion_id', 'etudiant_id', 'periode_id']);
            $table->index(['isArchived']);
            $table->softDeletes();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
