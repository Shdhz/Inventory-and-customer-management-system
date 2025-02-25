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
        Schema::table('tb_form_po', function (Blueprint $table) {
            $table->dropColumn('model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_form_po', function (Blueprint $table) {
            $table->string('model', 255)->nullable()->after('kategori_id');
        });
    }
};
