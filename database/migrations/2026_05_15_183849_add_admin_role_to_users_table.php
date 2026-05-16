<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
{
    DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('owner', 'client', 'admin') NOT NULL DEFAULT 'client'");
}

public function down(): void
{
    DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('owner', 'client') NOT NULL DEFAULT 'client'");
}
};