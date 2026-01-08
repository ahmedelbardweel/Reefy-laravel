<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('category', ['seeds', 'fertilizers', 'pesticides', 'equipment', 'harvest', 'other'])->default('other');
            $table->string('quantity')->nullable(); // String to allow "5 bags" or just store as number? Plan said decimal + unit. Let's do that.
            $table->decimal('quantity_value', 10, 2)->default(0); 
            $table->string('unit')->nullable(); // e.g., kg, liter, piece
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
};
