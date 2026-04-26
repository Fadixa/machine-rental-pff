<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('machines', function (Blueprint $table) {
        $table->id();
        $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
        $table->string('name');
        $table->string('type', 100)->nullable();
        $table->text('description')->nullable();
        $table->decimal('price_per_day', 10, 2);
        $table->decimal('price_per_hour', 10, 2);
        $table->string('location', 255)->nullable();
        $table->enum('status', ['available', 'unavailable'])->default('available');
        $table->timestamps();

        // Index pour la recherche et les filtres
        $table->index('status');
        $table->index('type');
        $table->index('location');
    });
}
public function down(): void
{
    Schema::dropIfExists('machines');
}
  
};