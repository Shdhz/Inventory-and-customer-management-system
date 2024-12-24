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
        Schema::create('tb_invoice_detail', function (Blueprint $table) {
            $table->id('invoice_detail_id');
            $table->foreignId('invoice_id');
            $table->foreignId('transaksi_detail_id');

            $table->foreign('invoice_id')->references('invoice_id')->on('tb_invoice')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('transaksi_detail_id')->references('id_transaksi_detail')->on('tb_transaksi_detail')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_invoice_detail');
    }
};
