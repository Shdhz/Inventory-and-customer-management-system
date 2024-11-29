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
        Schema::create('tb_customer_orders', function (Blueprint $table) {
            $table->id('customer_order_id');
            $table->unsignedBigInteger('draft_customer_id');
            $table->enum('tipe_order', ['cash', 'cashless']);
            $table->string('sumber', 50); // varchar(50)
            $table->enum('jenis_order', ['pre order', 'ready stock']); 
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Relasi dengan tb_draft_customer
            $table->foreign('draft_customer_id')->references('draft_customers_id')->on('tb_draft_customers')->onDelete('cascade'); // Hapus order jika draft_customer dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_customer_orders');
    }
};
