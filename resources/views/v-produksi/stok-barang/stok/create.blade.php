@extends('layouts.produksi')
@section('content')
    <x-message.errors />
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-header row-cols-auto">
                <div class="col">
                    {{-- Component backurl --}}
                    <x-button.backUrl href="{{ $backUrl }}" />
                </div>
                <div class="col px-2">
                    <h2 class="page-title">{{ $title }}</h2>
                </div>
            </div>
            <form action="{{ route('stok-barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <!-- Kolom Kiri -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Pilih Kategori <span class="text-danger">*</span></label>
                                <select class="form-select @error('nama_kategori') is-invalid @enderror" name="kategori_id" id="kategori_id" required>
                                    <option value="" selected>-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id_kategori }}" 
                                            data-kelompok="{{ $category->kelompok_produksi }}" 
                                            data-prefix="{{ substr(strtoupper($category->nama_kategori), 0, 2) }}" 
                                            {{ old('kategori_id') == $category->id_kategori ? 'selected' : '' }}>
                                            {{ $category->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>                                  
                                @error('nama_kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kode Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kode_produk" id="kode_produk" value="{{ old('kode_produk') }}" readonly required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_produk"
                                    placeholder="Masukkan jenis produk" value="{{ old('nama_produk') }}" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto Produk</label>
                                <input type="file" class="form-control" id="foto_produk" name="foto_produk"
                                    accept="image/*" />
                                <img class="mt-2" id="previewImage" src="#" alt="Preview Gambar"
                                    style="max-width: 200px; display: none;" />
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Kelompok Produksi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kelompok_produksi" id="kelompok_produksi"  value="{{ old('kelompok_produksi') }}" readonly />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="jumlah_stok"
                                    placeholder="Masukkan jumlah stok" value="{{ old('jumlah_stok') }}" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah Rusak</label>
                                <input type="number" class="form-control" name="jumlah_rusak"
                                    placeholder="Masukkan jumlah barang rusak" value="{{ old('jumlah_rusak') }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const namaKategori = document.getElementById('kategori_id');
        const kelompokProduksi = document.getElementById('kelompok_produksi');
        const kodeProduk = document.getElementById('kode_produk');

        // Map kategori ke prefix
        const prefixMap = {
            "bambu": "BU",
            "box": "BX",
            "tas": "TS"
        };

        namaKategori.addEventListener('change', async function() {
            const selectedOption = this.options[this.selectedIndex];
            const kelompok = selectedOption.getAttribute('data-kelompok');
            const kategori = selectedOption.textContent.trim().toLowerCase();

            // Set kelompok produksi
            kelompokProduksi.value = kelompok || '';

            // Validasi kategori
            const prefix = prefixMap[kategori];
            if (!prefix) {
                kodeProduk.value = '';
                alert("Kategori tidak dikenali untuk penentuan kode produk.");
                return;
            }

            // Fetch kode produk
            try {
                const response = await fetch(`/api/generate-kode-produk?prefix=${prefix}`);
                if (!response.ok) {
                    throw new Error('Gagal mendapatkan kode produk dari server');
                }
                const data = await response.json();
                kodeProduk.value = data.kode_produk;
            } catch (error) {
                console.error('Error:', error);
                alert("Terjadi kesalahan saat menghasilkan kode produk.");
            }
        });

        // Preview Foto Produk
        document.getElementById('foto_produk').addEventListener('change', function(event) {
            const preview = document.getElementById('previewImage');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = "none";
            }
        });
    </script>
@endsection
