<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('numero_permis')->unique();
            $table->enum('categorie_permis', ['B', 'C', 'D', 'E', 'F'])->default('C');
            $table->enum('statut', ['disponible', 'en_mission', 'indisponible', 'conge'])->default('disponible');
            $table->text('competences')->nullable(); // JSON : types de machines maîtrisées
            $table->string('telephone')->nullable();
            $table->decimal('latitude_actuelle', 10, 7)->nullable();
            $table->decimal('longitude_actuelle', 10, 7)->nullable();
            $table->timestamp('derniere_position_at')->nullable();
            $table->integer('missions_completees')->default(0);
            $table->decimal('note_moyenne', 3, 2)->default(0.00);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};