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
        // Menghapus kolom 'prioritas' dari tabel tb_form_po
        Schema::table('tb_form_po', function (Blueprint $table) {
            $table->dropColumn('prioritas');
        });

        // Menambahkan kolom 'prioritas' ke tabel tb_rencana_produksi
        Schema::table('tb_rencana_produksi', function (Blueprint $table) {
            $table->tinyInteger('prioritas')->default(1)->after('status'); // 1: Rendah, 2: Sedang, 3: Tinggi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menambahkan kembali kolom 'prioritas' ke tabel tb_form_po
        Schema::table('tb_form_po', function (Blueprint $table) {
            $table->tinyInteger('prioritas')->default(1)->after('metode_pembayaran'); // 1: Rendah, 2: Sedang, 3: Tinggi
        });

        // Menghapus kolom 'prioritas' dari tabel tb_rencana_produksi
        Schema::table('tb_rencana_produksi', function (Blueprint $table) {
            $table->dropColumn('prioritas');
        });
    }
};
