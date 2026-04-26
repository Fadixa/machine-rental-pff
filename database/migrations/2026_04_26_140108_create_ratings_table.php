<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('ratings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('machine_id')->constrained()->onDelete('cascade');
        $table->unsignedTinyInteger('rating'); // 1 à 5
        $table->text('comment')->nullable();
        $table->timestamps();

        // Un client ne peut noter une machine qu'une seule fois
        $table->unique(['client_id', 'machine_id']);
    });
}
public function down(): void
{
    Schema::dropIfExists('ratings');
}
};