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
        Schema::table('tb_draft_customers', function (Blueprint $table) {
            // Mengubah kolom menjadi nullable
            $table->string('email', 100)->nullable()->change();
            $table->string('provinsi', 50)->nullable()->change();
            $table->string('kota', 50)->nullable()->change();
            $table->text('alamat_lengkap')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_draft_customers', function (Blueprint $table) {
            // Mengembalikan kolom menjadi non-nullable
            $table->string('email', 100)->nullable(false)->change();
            $table->string('provinsi', 50)->nullable(false)->change();
            $table->string('kota', 50)->nullable(false)->change();
            $table->text('alamat_lengkap')->nullable(false)->change();
        });
    }
};
