<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiche_caravanes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Localisation
            $table->string('region');
            $table->string('departement');
            
            // Date & Horaires
            $table->date('date_caravane');
            $table->string('heure_debut')->nullable();
            $table->string('heure_fin')->nullable();
            
            // Distributeurs
            $table->text('distributeurs_beneficiaires')->nullable();
            $table->text('distributeurs_contacts')->nullable();
            
            // Itinéraire
            $table->text('itineraire')->nullable();
            
            // Logistique utilisée (véhicule, sono, audiovisuel, motos, autres)
            $table->json('moyens_logistiques')->nullable();
            
            // Couverture
            $table->integer('nb_villages_sillonnes')->default(0);
            
            // Supports communication distribués
            $table->integer('supports_affiches')->default(0);
            $table->integer('supports_depliants')->default(0);
            $table->string('supports_autres_libelle')->nullable();
            $table->integer('supports_autres_nb')->default(0);
            
            // Indicateurs
            $table->integer('nb_fa_vendus')->default(0);
            
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

        // Table des villages/quartiers touchés
        Schema::create('caravane_villages_touches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiche_caravane_id')->constrained('fiche_caravanes')->cascadeOnDelete();
            $table->integer('numero_ordre')->default(1);
            $table->string('commune_ou_zone')->nullable();
            $table->string('nom_village_quartier');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caravane_villages_touches');
        Schema::dropIfExists('fiche_caravanes');
    }
};
