<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('nom');
            $table->timestamps();
        });

        Schema::create('departements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->string('nom');
            $table->timestamps();
        });

        Schema::create('communes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departement_id')->constrained()->cascadeOnDelete();
            $table->string('nom');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communes');
        Schema::dropIfExists('departements');
        Schema::dropIfExists('regions');
    }
};
