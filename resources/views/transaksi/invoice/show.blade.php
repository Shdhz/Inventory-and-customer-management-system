
<div class="container-lg mt-2">
    <div class="card">
        <div class="card-header row-cols-auto">
            {{-- <div class="col">
                <x-button.backUrl href="{{ $backUrl }}" />
            </div> --}}
            <div class="col px-2">
                <h2 class="page-title">{{ $title }}</h2>
            </div>
        </div>
    </div>
    <form method="POST" action="">
        @csrf
        <div class="card mt-3 p-2">
            <div class="card-body">
                <!-- Informasi Produk -->
                <div class="row mb-3">
                    <div class="col">
                        <label for="produk_id" class="form-label">Pilih Produk</label>
                        <select id="produk_id" class="form-select">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                    data-nama="{{ $product->nama }}" 
                                    data-harga="{{ $product->harga }}">
                                    {{ $product->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label for="nama_produk" class="form-label">Nama Produk</label>
                        <input type="text" id="nama_produk" class="form-control" readonly>
                    </div>
                    <div class="col">
                        <label for="harga_produk" class="form-label">Harga Produk</label>
                        <input type="text" id="harga_produk" class="form-control" readonly>
                    </div>
                </div>

                <!-- Tabel Barang -->
                <table class="table table-bordered" id="barang-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be added dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const produkSelect = document.getElementById('produk_id');
        const namaProdukInput = document.getElementById('nama_produk');
        const hargaProdukInput = document.getElementById('harga_produk');

        produkSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const namaProduk = selectedOption.dataset.nama || '';
            const hargaProduk = selectedOption.dataset.harga || '';

            namaProdukInput.value = namaProduk;
            hargaProdukInput.value = hargaProduk;
        });
    });
</script>
