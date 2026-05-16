<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->enum('statut', [
                'assignee',      // Mission créée et assignée au chauffeur
                'en_route',      // Chauffeur en route vers le site
                'sur_place',     // Arrivé sur le chantier
                'en_cours',      // Mission en cours d'exécution
                'terminee',      // Mission complétée
                'annulee'        // Mission annulée
            ])->default('assignee');

            // Localisation départ (dépôt / position machine)
            $table->decimal('lat_depart', 10, 7)->nullable();
            $table->decimal('lng_depart', 10, 7)->nullable();
            $table->string('adresse_depart')->nullable();

            // Localisation destination (chantier client)
            $table->decimal('lat_destination', 10, 7)->nullable();
            $table->decimal('lng_destination', 10, 7)->nullable();
            $table->string('adresse_destination')->nullable();

            // Position en temps réel du chauffeur
            $table->decimal('lat_actuelle', 10, 7)->nullable();
            $table->decimal('lng_actuelle', 10, 7)->nullable();

            // Planning
            $table->timestamp('heure_depart_prevue')->nullable();
            $table->timestamp('heure_depart_reelle')->nullable();
            $table->timestamp('heure_arrivee_prevue')->nullable();
            $table->timestamp('heure_arrivee_reelle')->nullable();
            $table->timestamp('heure_fin_reelle')->nullable();

            // Distance et durée
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->integer('duree_estimee_min')->nullable();

            // Notes
            $table->text('instructions')->nullable();
            $table->text('notes_chauffeur')->nullable();
            $table->integer('note_client')->nullable(); // 1-5
            $table->text('commentaire_client')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};