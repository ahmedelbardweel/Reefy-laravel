<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Check and add 'priority'
            if (!Schema::hasColumn('tasks', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('due_date');
            }
            
            // Check and add 'category'
            if (!Schema::hasColumn('tasks', 'category')) {
                $table->enum('category', ['irrigation', 'fertilization', 'harvest', 'inspection', 'other'])->default('other')->after('status');
            }

            // Check and add 'description' (just in case)
            if (!Schema::hasColumn('tasks', 'description')) {
                $table->text('description')->nullable()->after('title');
            }

            // Check and add 'due_date' (just in case)
            if (!Schema::hasColumn('tasks', 'due_date')) {
                $table->dateTime('due_date')->after('description');
            }

            // Check and add 'status' (just in case)
            if (!Schema::hasColumn('tasks', 'status')) {
                $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending')->after('priority');
            }
            
            // Check and add 'completed_at'
            if (!Schema::hasColumn('tasks', 'completed_at')) {
                 $table->timestamp('completed_at')->nullable()->after('category');
            }
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $columns = ['priority', 'category', 'description', 'due_date', 'status', 'completed_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('tasks', $column)) {
                   // We generally don't want to drop these in a fix migration down method to avoid dataloss on rollback of a fix
                   // but for symmetry/correctness of 'down':
                   // $table->dropColumn($column); 
                }
            }
        });
    }
};
