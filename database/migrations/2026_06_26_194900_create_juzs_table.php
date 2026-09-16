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
        Schema::create('juzs', function(Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('number')->unique();
            $table->unsignedInteger('first_verse_id')->nullable();
            $table->unsignedInteger('last_verse_id')->nullable();
            $table->string('first_verse_key')->nullable();
            $table->string('last_verse_key')->nullable();
            $table->unsignedSmallInteger('verses_count')->default(0);
            $table->json('verse_mapping')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juzs');
    }
};