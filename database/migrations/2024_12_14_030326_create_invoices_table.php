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
        Schema::create('tb_invoice', function (Blueprint $table) {
            $table->id('invoice_id');
            $table->foreignId('form_po_id');
            $table->foreignId('transaksi_detail_id');
            $table->enum('status_pembayaran', ['lunas', 'belum lunas'])->default('belum lunas');
            $table->decimal('harga_satuan');
            $table->decimal('subtotal');
            $table->decimal('jumlah');
            $table->decimal('ongkir')->default(0);
            $table->decimal('down_payment')->default(0);
            $table->decimal('total');
            $table->date('tenggat_invoice')->nullable();
            $table->timestamps();

            $table->foreign('form_po_id')->references('id_form_po')->on('tb_form_po')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('transaksi_detail_id')->references('id_transaksi_detail')->on('tb_transaksi_detail')->onDelete('cascade')->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_invoice');
    }
};
