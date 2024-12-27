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
        Schema::create('tb_invoice_form_po', function (Blueprint $table) {
            $table->id('id_invoice_form_po');
            $table->foreignId('invoice_id');
            $table->foreignId('form_po_id');
            $table->timestamps();

            $table->foreign('invoice_id')->references('invoice_id')->on('tb_invoice')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('form_po_id')->references('id_form_po')->on('tb_form_po')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_invoice_form_po');
    }
};
