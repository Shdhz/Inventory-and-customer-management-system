@extends('layouts.admin')

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
            text-align: center;
        }

        .custom-file-upload label:hover {
            background-color: #f5f5f5;
            border: 2px dashed #381bdd;
            color: black;
        }

        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .image-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
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
                    <h2 class="page-title">Tambah Form PO</h2>
                </div>
            </div>
            <form action="{{ route('form-po.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <!-- Kolom Kiri -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Nama Customer</label>
                                <select class="form-control @error('customer_order_id') is-invalid @enderror"
                                    name="customer_order_id" required>
                                    <option value="" selected>-- Pilih Nama Customer --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->customer_order_id }}"
                                            {{ old('customer_order_id') == $customer->customer_order_id ? 'selected' : '' }}>
                                            {{ $customer->draftCustomer->Nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_order_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Model</label>
                                <div class="custom-file-upload">
                                    <input type="file" class="form-control" id="model" name="model[]"
                                        accept="image/*" multiple />
                                    <label for="model"
                                        class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-camera-fill me-2"></i>Upload Gambar <span class="text-muted"> - Maksimal upload ukuran/gambar : 2Mb</span>
                                    </label>
                                </div>
                                <div class="image-preview mt-2" id="imagePreviewContainer">
                                    <!-- Preview gambar akan ditampilkan di sini -->
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Qty <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('qty') is-invalid @enderror" name="qty"
                                    placeholder="Jumlah" value="{{ old('qty') }}" required>
                                @error('qty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ukuran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ukuran') is-invalid @enderror"
                                    name="ukuran" placeholder="Ukuran" value="{{ old('ukuran') }}" required>
                                @error('ukuran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Warna <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('warna') is-invalid @enderror"
                                    name="warna" placeholder="Warna" value="{{ old('warna') }}" required>
                                @error('warna')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Bahan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('bahan') is-invalid @enderror"
                                    name="bahan" placeholder="Bahan" value="{{ old('bahan') }}" required>
                                @error('bahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Aksesoris <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('aksesoris') is-invalid @enderror"
                                    name="aksesoris" placeholder="Aksesoris" value="{{ old('aksesoris') }}">
                                @error('aksesoris')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori Barang <span class="text-danger">*</span></label>
                                <select class="form-control @error('kategori_id') is-invalid @enderror" name="kategori_id"
                                    required>
                                    <option value="" selected>-- Pilih Kategori Barang --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id_kategori }}"
                                            {{ old('kategori_id') == $category->id_kategori ? 'selected' : '' }}>
                                            {{ $category->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select class="form-control @error('metode_pembayaran') is-invalid @enderror"
                                    name="metode_pembayaran" required>
                                    <option value="" selected>-- Pilih Metode Pembayaran --</option>
                                    <option value="cod" {{ old('metode_pembayaran') == 'cod' ? 'selected' : '' }}>COD
                                    </option>
                                    <option value="transfer"
                                        {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                                @error('metode_pembayaran')
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
    <script>
        const modelInput = document.getElementById('model');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const errorMessage = document.createElement('div');
        errorMessage.style.color = 'red';
        errorMessage.style.marginTop = '5px';
        modelInput.parentElement.appendChild(errorMessage); 

        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        const maxSize = 5 * 1024 * 1024; 
        let selectedFiles = [];

        // Fungsi untuk menampilkan preview gambar
        function displayImagePreview(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px'; 
                img.style.height = '100px'; 
                img.style.objectFit = 'cover';
                img.style.borderRadius = '8px';
                img.style.marginBottom = '10px';
                imagePreviewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        }

        // Fungsi untuk menangani perubahan input file
        modelInput.addEventListener('change', function(event) {
            const files = event.target.files;

            errorMessage.textContent = '';

            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];


                    if (!allowedTypes.includes(file.type)) {
                        errorMessage.textContent = 'File type not allowed. Only JPG, JPEG, and PNG are allowed.';
                        return;
                    }

                    if (file.size > maxSize) {
                        errorMessage.textContent = 'File is too large. Maximum size allowed is 5MB.';
                        return;
                    }

                    // Tambahkan file ke array selectedFiles jika belum ada
                    if (!selectedFiles.some(existingFile => existingFile.name === file.name && existingFile.size ===
                            file.size)) {
                        selectedFiles.push(file);
                        displayImagePreview(file); 
                    }
                }

                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                modelInput.files = dataTransfer.files;
            }
        });

        imagePreviewContainer.addEventListener('click', function(event) {
            if (event.target.tagName === 'IMG') {
                const imgSrc = event.target.src;
                const index = selectedFiles.findIndex(file => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    return reader.result === imgSrc;
                });

                if (index !== -1) {
                    selectedFiles.splice(index, 1); 
                    event.target.remove();

                    // Update input file dengan file-file yang tersisa
                    const dataTransfer = new DataTransfer();
                    selectedFiles.forEach(file => dataTransfer.items.add(file));
                    modelInput.files = dataTransfer.files;
                }
            }
        });
    </script>
@endsection
