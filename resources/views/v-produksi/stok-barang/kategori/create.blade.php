@extends('layouts.produksi')
@section('content')
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-header row-cols-auto">
                <div class="col">
                    <span>
                        {{-- component backurl --}}
                        <x-button.backUrl href="{{ $backUrl }}" />
                    </span>
                </div>
                <div class="col px-2    ">
                    <h2 class="page-title">{{ $title }}</h2>
                </div>
            </div>
            <form action="{{ route('kategori-barang.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <!-- Kolom Kiri -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Nama Kategori barang</label>
                                <input type="text" class="form-control" name="nama_kategori" placeholder="nama kategori"
                                    required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kelompok Produksi</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>-- Pilih Kelompok Produksi --</option>
                                    <option value="kelompok_produksi">1</option>
                                    <option value="kelompok_produksi">2</option>
                                    <option value="kelompok_produksi">3</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
