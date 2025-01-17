@extends('layouts.produksi')

@section('content')
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
            <form action="{{ route('barang-masuk.update', $barangMasuk->id_barang_masuk) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <!-- Kolom Kiri -->
                        <div class="flex-grow-1">
                            <!-- Tanggal Masuk -->
                            <div class="mb-3">
                                <label for="tanggal_barang_masuk" class="form-label">Tanggal Masuk <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="tanggal_barang_masuk" id="tanggal_barang_masuk"
                                    class="form-control @error('tanggal_barang_masuk') is-invalid @enderror"
                                    value="{{ old('tanggal_barang_masuk', \Carbon\Carbon::parse($barangMasuk->tanggal_barang_masuk)->format('Y-m-d')) }}"
                                    required />
                                @error('tanggal_barang_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Pilih Barang -->
                            <div class="mb-3">
                                <label for="stok_id" class="form-label">Pilih Barang <span
                                        class="text-danger">*</span></label>
                                <!-- Input text untuk menampilkan nama produk -->
                                <input type="text" class="form-control @error('stok_id') is-invalid @enderror"
                                    id="stok_id_display"
                                    value="{{ $barangMasuk->stok_id ? $stokBarang->firstWhere('id_stok', $barangMasuk->stok_id)->nama_produk : old('stok_id') }}"
                                    readonly />
                                <!-- Hidden input untuk menyimpan stok_id yang sebenarnya -->
                                <input type="hidden" name="stok_id" id="stok_id"
                                    value="{{ $barangMasuk->stok_id ? $barangMasuk->stok_id : old('stok_id') }}" />
                                @error('stok_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jumlah Barang -->
                            <div class="mb-3">
                                <label for="jumlah_barang_masuk" class="form-label">Jumlah Barang Masuk <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="jumlah_barang_masuk" id="jumlah_barang_masuk"
                                    class="form-control @error('jumlah_barang_masuk') is-invalid @enderror"
                                    value="{{ old('jumlah_barang_masuk', $barangMasuk->jumlah_barang_masuk) }}"
                                    min="1" required />
                                @error('jumlah_barang_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="card-footer d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
