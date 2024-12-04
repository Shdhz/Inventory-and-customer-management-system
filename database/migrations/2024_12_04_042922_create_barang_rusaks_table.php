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
        Schema::create('tb_barang_rusak', function (Blueprint $table) {
            $table->id('barang_rusak_id');
            $table->unsignedBigInteger('stok_id');
            $table->integer('jumlah_barang_rusak');
            $table->timestamps();

            // Foreign Key
            $table->foreign('stok_id')->references('id_stok')->on('tb_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_barang_rusak');
    }
};
