<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     */
    public function up(): void
    {
        Schema::create('tb_transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->foreignId('customer_order_id');
            $table->decimal('diskon_produk', 10,2)->nullable();
            $table->decimal('diskon_ongkir')->nullable();
            $table->string('ekspedisi')->nullable();
            $table->enum('metode_pembayaran', ['cod', 'transfer']);
            $table->timestamps();
            
            // relasi ke tb customer order
            $table->foreign('customer_order_id')->references('customer_order_id')->on('tb_customer_orders')->onDelete('cascade');
        });

        // transaksi detail
        Schema::create('tb_transaksi_detail', function (Blueprint $table) {
            $table->id('id_transaksi_detail');
            $table->foreignId('stok_id');
            $table->foreignId('transaksi_id');
            $table->integer('qty');
            $table->decimal('harga_satuan');
            $table->decimal('subtotal');
            $table->decimal('jumlah'); 
            $table->dateTime('tanggal_keluar');
            $table->timestamps();

            // relasi ke tb customer order
            $table->foreign('stok_id')->references('id_stok')->on('tb_products')->onDelete('cascade');
            $table->foreign('transaksi_id')->references('id_transaksi')->on('tb_transaksi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_transaksi');
        Schema::dropIfExists('tb_transaksi_detail');
    }
};
