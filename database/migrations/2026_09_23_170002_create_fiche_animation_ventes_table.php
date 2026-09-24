<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiche_animation_ventes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Localisation
            $table->string('region');
            $table->string('departement');
            $table->string('commune');
            $table->string('village_quartier');
            $table->string('lieu_animation');
            
            // Date
            $table->date('date_animation');
            
            // Distributeur
            $table->string('distributeur_nom');
            $table->string('distributeur_adresse')->nullable();
            $table->string('distributeur_contact')->nullable();
            $table->longText('distributeur_signature')->nullable();
            
            // Animation
            $table->text('materiel_utilise')->nullable();
            $table->string('animateur_nom');
            $table->string('animateur_contact')->nullable();
            
            // Indicateurs
            $table->integer('nb_fa_vendus')->default(0);
            
            // Metadata terrain & offline sync
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('photo_url')->nullable();
            $table->string('sync_status')->default('synced'); // draft, pending, synced
            $table->timestamp('client_created_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiche_animation_ventes');
    }
};
