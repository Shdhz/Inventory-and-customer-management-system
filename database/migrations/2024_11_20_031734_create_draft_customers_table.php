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
        Schema::create('tb_draft_customers', function (Blueprint $table) {
            $table->id('draft_customers_id');
            $table->unsignedBigInteger('user_id');
            $table->string('Nama', 100);
            $table->string('no_hp', 15);
            $table->string('email', 100);
            $table->string('provinsi', 50);
            $table->string('kota', 50);
            $table->text('alamat_lengkap');
            $table->string('sumber', 50);
            $table->timestamps();

            // Adding foreign key constraint and index
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id'); // Add index for user_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_draft_customers');
    }
};
