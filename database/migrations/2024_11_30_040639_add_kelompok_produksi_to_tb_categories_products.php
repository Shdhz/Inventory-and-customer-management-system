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
        Schema::table('tb_categories_products', function (Blueprint $table) {
            Schema::table('tb_categories_products', function (Blueprint $table) {
                // Adding the 'kelompok_produksi' column with enum values
                $table->enum('kelompok_produksi', [1, 2, 3])->comment('1 = Box, 2 = Bambu, 3 = Tas');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_categories_products', function (Blueprint $table) {
            $table->dropColumn('kelompok_produksi');
        });
    }
};
