@extends('layouts.produksi')
@section('content')
    <style>
        .custom-file-upload input[type="file"] {
            display: none;
            /* Hide the file input element */
        }

        .custom-file-upload label {
            cursor: pointer;
            padding: 10px 20px;
            border: 2px dashed #381bdd;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .custom-file-upload label:hover {
            color: black border: 2px dashed #381bdd;
        }

        .image-preview img {
            max-height: 300px;
            object-fit: contain;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>

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
                            <!-- Pilih Kategori -->
                            <div class="mb-3">
                                <label class="form-label">Pilih Kategori <span class="text-danger">*</span></label>
                                <select class="form-select @error('kategori_id') is-invalid @enderror" name="kategori_id"
                                    id="kategori_id" required>
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
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kode Produk -->
                            <div class="mb-3">
                                <label class="form-label">Kode Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kode_produk') is-invalid @enderror"
                                    name="kode_produk" id="kode_produk" value="{{ old('kode_produk') }}" readonly
                                    required />
                                @error('kode_produk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jenis Produk -->
                            <div class="mb-3">
                                <label class="form-label">Jenis Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_produk') is-invalid @enderror"
                                    name="nama_produk" placeholder="Masukkan jenis produk" value="{{ old('nama_produk') }}"
                                    required />
                                @error('nama_produk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="flex-grow-1">
                            <!-- Kelompok Produksi -->
                            <div class="mb-3">
                                <label class="form-label">Kelompok Produksi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('kelompok_produksi') is-invalid @enderror"
                                    name="kelompok_produksi" id="kelompok_produksi" value="{{ old('kelompok_produksi') }}"
                                    readonly />
                                @error('kelompok_produksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jumlah Stok -->
                            <div class="mb-3">
                                <label class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('jumlah_stok') is-invalid @enderror"
                                    name="jumlah_stok" placeholder="Masukkan jumlah stok" value="{{ old('jumlah_stok') }}"
                                    required />
                                @error('jumlah_stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Foto Produk -->
                            <div class="mb-3">
                                <label class="form-label">Foto Produk</label>
                                <div class="custom-file-upload">
                                    <input type="file" class="form-control @error('foto_produk') is-invalid @enderror"
                                        id="foto_produk" name="foto_produk" accept="image/*" />
                                    <label for="foto_produk"
                                        class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-camera-fill me-2"></i> Upload Foto
                                    </label>
                                    <div class="form-text">Upload gambar maksimal ukuran 2 Mb.</div>
                                </div>
                                @error('foto_produk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="image-preview mt-2">
                                    <img id="previewImage" src="#" alt="Preview Gambar"
                                        style="max-width: 100%; display: none; border-radius: 8px;" />
                                </div>
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
            "tas": "TS",
            "home decor": "HD"
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
    </script>
@endsection
