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
            <form action="{{ route('barang-rusak.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <!-- Kolom Kiri -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label for="id_stok" class="form-label">Pilih Barang Rusak <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('id_stok') is-invalid @enderror" name="id_stok"
                                    id="id_stok" required>
                                    <option value="" selected>-- Pilih Barang --</option>
                                    @foreach ($stokBarang as $sb)
                                        <option value="{{ $sb->id_stok }}"
                                            {{ (int) old('id_stok') === (int) $sb->id_stok ? 'selected' : '' }}>
                                            {{ $sb->nama_produk }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah barang rusak<span class="text-danger">*</span></label>
                                <input type="number"
                                    class="form-control @error('jumlah_barang_rusak') is-invalid @enderror"
                                    name="jumlah_barang_rusak" id="jumlah_barang_rusak"
                                    value="{{ old('jumlah_barang_rusak') }}" required />
                                @error('jumlah_barang_rusak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
    {{-- <script>
        const namaKategori = document.getElementById('kategori_id');
        const kelompokProduksi = document.getElementById('kelompok_produksi');
        const kodeProduk = document.getElementById('kode_produk');
    
        // Map kategori ke prefix
        const prefixMap = {
            "bambu": "BU",
            "box": "BX",
            "tas": "TS"
        };
    
        // Function to generate a random 4-digit number
        function generateRandomNumber() {
            return Math.floor(1000 + Math.random() * 9000); // Generate random number between 1000 and 9999
        }
    
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
    
            // Generate random number for kode produk if no backend call is needed
            const randomNumber = generateRandomNumber(); // Random number from 1000 to 9999
            const newKodeProduk = prefix + randomNumber;
    
            // Set the kode produk in the input field
            kodeProduk.value = newKodeProduk;
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
    </script>     --}}
@endsection
