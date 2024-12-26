<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attribute_choice_product', function (Blueprint $table) {
            $table->unsignedBigInteger('attribute_choice_id');
            $table->unsignedBigInteger('product_id');

            $table->primary(['attribute_choice_id', 'product_id']);

            $table->foreign('attribute_choice_id')
                ->references('id')
                ->on('attribute_choices')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_choice_product');
    }
};
