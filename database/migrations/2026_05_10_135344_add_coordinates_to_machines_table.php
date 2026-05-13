<?php
// database/migrations/xxxx_add_coordinates_to_machines_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machines', function (Blueprint $table) {

            $table->decimal('latitude', 10, 7)->nullable()->after('price_per_day');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('city')->nullable()->after('longitude'); // مدينة الآلة
        });
    }

    public function down(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'city']);
        });
    }
};