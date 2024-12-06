@extends('layouts.admin')

@section('content')
    <x-message.errors />
    <style>
        .custom-file-upload input[type="file"] {
            display: none; /* Hide the file input element */
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
                                <select class="form-control @error('customer_order_id') is-invalid @enderror" name="customer_order_id" required>
                                    <option value="" selected>-- Pilih Nama Customer --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->customer_order_id }}" {{ old('customer_order_id') == $customer->customer_order_id ? 'selected' : '' }}>
                                            {{ $customer->draftCustomer->Nama  }}
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
                                    <input type="file" class="form-control" id="model" name="model" accept="image/*" />
                                    <label for="model" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-camera-fill me-2"></i> Upload Gambar
                                    </label>
                                </div>
                                <div class="image-preview mt-2">
                                    <img id="previewImage" src="#" alt="Preview Gambar" style="max-width: 100%; display: none; border-radius: 8px;" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Qty <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('qty') is-invalid @enderror" name="qty" placeholder="Jumlah" value="{{ old('qty') }}" required>
                                @error('qty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ukuran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ukuran') is-invalid @enderror" name="ukuran" placeholder="Ukuran" value="{{ old('ukuran') }}" required>
                                @error('ukuran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Warna <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('warna') is-invalid @enderror" name="warna" placeholder="Warna" value="{{ old('warna') }}" required>
                                @error('warna')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Bahan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('bahan') is-invalid @enderror" name="bahan" placeholder="Bahan" value="{{ old('bahan') }}" required>
                                @error('bahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Aksesoris <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('aksesoris') is-invalid @enderror" name="aksesoris" placeholder="Aksesoris" value="{{ old('aksesoris') }}">
                                @error('aksesoris')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori Barang <span class="text-danger">*</span></label>
                                <select class="form-control @error('kategori_id') is-invalid @enderror" name="kategori_id" required>
                                    <option value="" selected>-- Pilih Kategori Barang --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id_kategori }}" {{ old('kategori_id') == $category->id_kategori ? 'selected' : '' }}>
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
                                <select class="form-control @error('metode_pembayaran') is-invalid @enderror" name="metode_pembayaran" required>
                                    <option value="" selected>-- Pilih Metode Pembayaran --</option>
                                    <option value="cod" {{ old('metode_pembayaran') == 'cod' ? 'selected' : '' }}>COD</option>
                                    <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
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
        const previewImage = document.getElementById('previewImage');
        const errorMessage = document.createElement('div');
        errorMessage.style.color = 'red';
        errorMessage.style.marginTop = '5px';
        modelInput.parentElement.appendChild(errorMessage); // Append error message below the input
    
        // Allowed image types and maximum size (5MB)
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        const maxSize = 5 * 1024 * 1024; // 5MB max size
    
        // Preview Foto Produk with validation
        modelInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
    
            // Clear previous error message
            errorMessage.textContent = '';
    
            if (file) {
                // Check file type
                if (!allowedTypes.includes(file.type)) {
                    errorMessage.textContent = 'File type not allowed. Only JPG, JPEG, and PNG are allowed.';
                    previewImage.style.display = 'none';
                    return;
                }
    
                // Check file size
                if (file.size > maxSize) {
                    errorMessage.textContent = 'File is too large. Maximum size allowed is 5MB.';
                    previewImage.style.display = 'none';
                    return;
                }
    
                // If validation passes, show the image preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.style.display = 'none';
            }
        });
    </script>
    
@endsection
