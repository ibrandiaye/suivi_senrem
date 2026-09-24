<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiche_emissions_radios', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Localisation
            $table->string('region');
            $table->string('commune');
            
            // Station
            $table->string('radio_nom');
            $table->string('radio_frequence')->nullable();
            
            // Date & Heures
            $table->date('date_emission');
            $table->string('heure_debut')->nullable();
            $table->string('heure_fin')->nullable();
            
            // Contenu
            $table->text('theme');
            $table->string('format')->default('direct'); // direct, différé
            $table->string('lieu')->default('studio'); // studio, hors studio
            
            // Animateur
            $table->string('animateur_nom');
            
            // Responsable radio
            $table->string('responsable_radio_nom')->nullable();
            $table->longText('responsable_radio_signature')->nullable();
            
            // Media
            $table->string('audio_or_photo_url')->nullable();
            
            // Metadata terrain & offline sync
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('sync_status')->default('synced');
            $table->timestamp('client_created_at')->nullable();
            
            $table->timestamps();
        });

        // Table des participants à l'émission
        Schema::create('radio_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiche_emissions_radio_id')->constrained('fiche_emissions_radios')->cascadeOnDelete();
            $table->integer('numero_ordre')->default(1);
            $table->string('prenom_nom');
            $table->string('sexe', 1)->default('M'); // M ou F
            $table->string('profession')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('radio_participants');
        Schema::dropIfExists('fiche_emissions_radios');
    }
};
