<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiche_leaders_opinions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Localisation
            $table->string('region');
            $table->string('departement');
            $table->string('commune');
            $table->string('village_quartier');
            $table->string('lieu_habitation')->nullable();
            
            // Identité
            $table->string('prenom_nom');
            $table->string('sexe', 1)->default('M'); // M ou F
            $table->string('titre_profession')->nullable();
            $table->string('telephone');
            $table->string('email')->nullable();
            
            // Engagement & influence
            $table->text('membre_entites')->nullable(); // association, GPF, OCB, autres...
            $table->text('fonctions_entites')->nullable();
            $table->text('localites_influence')->nullable();
            
            // Responsable
            $table->string('responsable_nom');
            $table->longText('responsable_signature')->nullable();
            
            // Metadata terrain & offline sync
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('photo_url')->nullable();
            $table->string('sync_status')->default('synced');
            $table->timestamp('client_created_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiche_leaders_opinions');
    }
};
