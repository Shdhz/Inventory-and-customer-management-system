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
        Schema::create('tb_form_po', function (Blueprint $table) {
            $table->id('id_form_po');
            $table->foreignId('customer_order_id');
            $table->foreignId('kategori_id');
            $table->string('model');
            $table->integer('qty');
            $table->string('ukuran');
            $table->string('bahan');
            $table->string('warna');
            $table->string('aksesoris');
            $table->text('keterangan')->nullable();
            $table->boolean('status_form_po')->default(0);
            $table->enum('metode_pembayaran', ['cod', 'transfer']);
            $table->tinyInteger('prioritas')->default(1);    // 1: Rendah, 2: Sedang, 3: Tinggi
            $table->timestamps();

            $table->foreign('customer_order_id')->references('customer_order_id')->on('tb_customer_orders')->onDelete('cascade');
            $table->foreign('kategori_id')->references('id_kategori')->on('tb_categories_products')->onDelete('cascade')->index();
        });

        Schema::create('tb_rencana_produksi', function (Blueprint $table) {
            $table->id('id_rencana_produksi');
            $table->foreignId('form_po_id');
            $table->string('nama_pengrajin');
            $table->dateTime('mulai_produksi');
            $table->dateTime('berakhir_produksi');
            $table->enum('status', ['produksi', 'selesai']);
            $table->timestamps();

            $table->foreign('form_po_id')->references('id_form_po')->on('tb_form_po')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_form_po');
        Schema::dropIfExists('tb_rencana_produksi');
    }
};
