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
        Schema::create('tb_model_form_po', function (Blueprint $table) {
            $table->id('id_model');
            $table->foreignId('id_form_po');
            $table->string('model');
            $table->timestamps();

            $table->foreign('id_form_po')->references('id_form_po')->on('tb_form_po')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_model_form_po');
    }
};
