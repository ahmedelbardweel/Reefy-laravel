<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('tasks', 'crop_id')) {
                $table->foreignId('crop_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            }
            
            // We might need to update the category enum if it exists but has old values
            // Or just ensure it exists. Since changing ENUMs is DB specific and can be tricky,
            // we will assume for now if it's there it might be the old one, but let's check basic columns first.
            // If the table was essentially empty/default, it likely had whatever the OLD migration had.
            // Let's just ensure user_id is there, which is the crashing error.
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['crop_id']);
            $table->dropColumn('crop_id');
        });
    }
};
