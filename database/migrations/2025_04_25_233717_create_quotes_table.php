<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();;
            $table->string('email');
            $table->string('phone')->nullable();;
            $table->string('source')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('quantity')->nullable();;
            $table->string('quantity2')->nullable();
            $table->string('quantity3')->nullable();
            $table->string('colors')->nullable();
            $table->string('product_name')->nullable();
            $table->string('box_style')->nullable();
            $table->string('units')->nullable();
            $table->string('stock')->nullable();
            $table->json('other_products')->nullable();
            $table->json('art_design')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->string('location')->nullable();
            $table->string('postal')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('quotes');
    }
};
