@extends('layouts.produksi')
@section('content')
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">{{ $title }}</h2>
                    </div>
                    <x-button.add-btn href="{{ route('kategori-barang.create') }}"/>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable" id="stok-barang" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama kategori barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection