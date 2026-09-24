<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiche_demonstrations_culinaires', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Localisation
            $table->string('region');
            $table->string('departement');
            $table->string('commune');
            $table->string('village_quartier');
            
            // Date
            $table->date('date_demonstration');
            
            // GPF bénéficiaire
            $table->string('gpf_nom');
            $table->text('activites_principales_gpf')->nullable();
            
            // Présence & Indicateurs Genre
            $table->integer('nb_presents')->default(0);
            $table->integer('nb_hommes')->default(0);
            $table->integer('nb_femmes')->default(0);
            
            // Présidente GPF
            $table->string('presidente_nom');
            $table->string('presidente_contact')->nullable();
            $table->integer('presidente_age')->nullable();
            
            // Consentements (OUI / NON)
            $table->boolean('consent_photo_video')->default(false);
            $table->boolean('consent_interview')->default(false);
            
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
        Schema::dropIfExists('fiche_demonstrations_culinaires');
    }
};
