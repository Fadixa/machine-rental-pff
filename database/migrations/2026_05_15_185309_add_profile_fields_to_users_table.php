<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Colonnes déjà présentes dans create_users_table — rien à faire
    }
    public function down(): void
    {
        // Rien à annuler
    }
};