@extends('layouts.produksi')
@section('content')
<x-message.success />
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">{{ $title }}</h2>
                    </div>
                    <x-button.add-btn href="{{ route('stok-barang.create') }}" />
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive overflow-auto">
                    <table class="table table-striped datatable" id="stok-barang" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode produk</th>
                                <th>Foto produk</th>
                                <th>Nama produk</th>
                                <th>Kategori</th>
                                <th>Kelompok Produksi</th>
                                <th>Jumlah stok</th>
                                <th>Barang rusak</th>
                                <th>Diperbarui tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#stok-barang').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('stok-barang.index') }}',
                error: function(xhr, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                }
            },
            dom: '<"d-flex justify-content-between align-items-center mb-3"<"entries-filter"l><"search-bar"f>>rt<"d-flex justify-content-between align-items-center"ip>',
            language: {
                search: '',
                searchPlaceholder: 'Cari...',
                lengthMenu: 'Tampilkan _MENU_ data',
                paginate: {
                    next: 'Berikutnya',
                    previous: 'Sebelumnya',
                },
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'kode_produk',
                    name: 'kode_produk'
                },
                {
                    data: 'foto_produk',
                    name: 'foto_produk'
                },
                {
                    data: 'nama_produk',
                    name: 'nama_produk'
                },
                {
                    data: 'nama_kategori',
                    name: 'nama_kategori'
                },
                {
                    data: 'kelompok_produksi',
                    name: 'kelompok_produksi'
                },
                {
                    data: 'jumlah_stok',
                    name: 'jumlah_stok'
                },
                {
                    data: 'jumlah_rusak',
                    name: 'jumlah_rusak'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
        });
    </script>
@endsection