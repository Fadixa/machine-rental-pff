<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('machine_id')->constrained()->onDelete('cascade');
        $table->date('start_date');
        $table->date('end_date');
        $table->decimal('total_price', 12, 2);
        $table->enum('status', ['pending', 'accepted', 'rejected', 'completed'])
              ->default('pending');
        $table->text('rejection_reason')->nullable();
        $table->timestamps();

        // Index crucial pour la vérification de disponibilité
        $table->index(['machine_id', 'start_date', 'end_date']);
        $table->index(['machine_id', 'status']);
    });
}
public function down(): void
{
    Schema::dropIfExists('reservations');
}
};