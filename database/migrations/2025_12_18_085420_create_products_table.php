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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Link to farmer, nullable for system products
            $table->string('name');
            $table->string('category'); // seeds, fertilizer, tools, pesticides
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_market_listed')->default(true);
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
        Schema::dropIfExists('products');
    }
};
