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
        Schema::table('tb_invoice', function (Blueprint $table) {
            // Menambahkan kolom nota_no dengan constraint unique
            $table->string('nota_no')->unique()->after('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_invoice', function (Blueprint $table) {
            // Menghapus kolom nota_no jika rollback
            $table->dropColumn('nota_no');
        });
    }
};
