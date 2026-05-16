<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
    
        if (!Schema::hasColumn('users', 'city'))   $table->string('city', 100)->nullable()->after('phone');
        if (!Schema::hasColumn('users', 'bio'))    $table->text('bio')->nullable()->after('city');
        if (!Schema::hasColumn('users', 'avatar')) $table->string('avatar')->nullable()->after('bio');
    });
}

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'city', 'bio', 'avatar']);
        });
    }
};