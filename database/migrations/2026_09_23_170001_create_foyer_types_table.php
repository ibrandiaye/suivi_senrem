<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foyer_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('nom');
            $table->string('combustible'); // Charbon, Bois, Bois/Charbon
            $table->string('capacite_kg')->nullable(); // 4kg, 7kg, 10kg, 15kg
            $table->text('description')->nullable();
            $table->integer('ordre')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foyer_types');
    }
};
