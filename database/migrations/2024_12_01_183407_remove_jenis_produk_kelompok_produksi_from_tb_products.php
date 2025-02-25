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
        Schema::table('tb_products', function (Blueprint $table) {
            $table->dropColumn(['jenis_produk', 'kelompok_produksi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_products', function (Blueprint $table) {
            $table->string('jenis_produk')->nullable();
            $table->string('kelompok_produksi')->nullable();
        });
    }
};
