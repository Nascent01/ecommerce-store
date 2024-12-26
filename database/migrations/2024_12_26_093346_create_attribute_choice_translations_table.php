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
        Schema::create('attribute_choice_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_choice_id');
            $table->string('locale', 5);
            $table->string('slug');
            $table->string('name');

            $table->unique(['attribute_choice_id', 'locale']);

            $table->foreign('attribute_choice_id')
                ->references('id')
                ->on('attribute_choices')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_choice_translations');
    }
};
