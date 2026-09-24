<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiche_ventes_distributeurs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Distributeur
            $table->string('distributeur_nom');
            $table->string('distributeur_statut')->nullable(); // Actif, Relais, Gérant, etc.
            $table->string('distributeur_telephone')->nullable();
            
            // Localisation
            $table->string('region');
            $table->string('departement');
            $table->string('commune');
            $table->string('village_quartier');
            $table->string('adresse_client')->nullable();
            
            // Date vente
            $table->date('date_vente');
            
            // Cumul total FA
            $table->integer('total_fa_vendus')->default(0);
            
            // Metadata terrain & offline sync
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->longText('distributeur_signature')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('sync_status')->default('synced');
            $table->timestamp('client_created_at')->nullable();
            
            $table->timestamps();
        });

        // Table des lignes de vente par type de foyer
        Schema::create('vente_distributeur_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiche_ventes_distributeur_id')->constrained('fiche_ventes_distributeurs')->cascadeOnDelete();
            $table->foreignId('foyer_type_id')->constrained('foyer_types');
            $table->integer('quantite')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vente_distributeur_items');
        Schema::dropIfExists('fiche_ventes_distributeurs');
    }
};
