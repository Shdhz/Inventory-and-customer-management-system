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
            $table->dropForeign(['transaksi_detail_id']);
            $table->dropColumn('transaksi_detail_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_invoice', function (Blueprint $table) {
            //
        });
    }
};
