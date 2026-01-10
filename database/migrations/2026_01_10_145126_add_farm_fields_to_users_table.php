<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('farm_name')->nullable()->after('profile_completed');
            $table->integer('farm_size')->nullable()->after('farm_name');
            $table->string('farm_location')->nullable()->after('farm_size');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['farm_name', 'farm_size', 'farm_location']);
        });
    }
};
