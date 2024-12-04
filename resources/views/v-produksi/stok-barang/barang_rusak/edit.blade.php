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
            <form action="{{ route('barang-rusak.update', $barangRusak->barang_rusak_id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                                            {{ (int) old('id_stok', $barangRusak->stok_id) === (int) $sb->id_stok ? 'selected' : '' }}>
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
                                    value="{{ old('jumlah_barang_rusak', $barangRusak->jumlah_barang_rusak) }}" required />
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
@endsection
