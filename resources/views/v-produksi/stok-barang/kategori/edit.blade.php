@extends('layouts.produksi')
@section('content')
<x-message.errors />
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
            <form action="{{ route('kategori-barang.update', $category->id_kategori) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <!-- Kolom Kiri -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Nama Kategori barang</label>
                                <input type="text" class="form-control" name="nama_kategori" placeholder="nama kategori"
                                    required value="{{ $category->nama_kategori }}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kelompok Produksi</label>
                                <select class="form-control" name="kelompok_produksi" required id="kelompok_produksi_select">
                                    <option selected>{{ $category->kelompok_produksi }}</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
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
