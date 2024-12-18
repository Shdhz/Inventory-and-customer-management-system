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
            Schema::create('tb_categories_products', function (Blueprint $table) {
                $table->id('id_kategori');
                $table->string('nama_kategori');
                $table->enum('kelompok_produksi', [1,2,3]);
                $table->timestamps();
            });

            Schema::create('tb_products', function (Blueprint $table) {
                $table->id('id_stok');
                $table->unsignedBigInteger('kategori_id');
                $table->string('kode_produk');
                $table->string('nama_produk');
                $table->string('jenis_produk');
                $table->enum('kelompok_produksi', [1, 2, 3]);
                $table->integer('jumlah_rusak');
                $table->integer('jumlah_stok');
                $table->string('foto_produk')->nullable();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('kategori_id')->references('id_kategori')->on('tb_categories_products')->onDelete('cascade');
                $table->index('kategori_id');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('tb_categories_products');
            Schema::dropIfExists('tb_products');
        }
    };
