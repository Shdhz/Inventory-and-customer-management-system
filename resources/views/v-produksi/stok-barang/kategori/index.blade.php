@extends('layouts.produksi')

@section('content')
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert"       >
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">{{ $title }}</h2>
                    </div>
                    <x-button.add-btn href="{{ route('kategori-barang.create') }}" />
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable" id="kategori-barang" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori Barang</th>
                                <th>Kelompok Produksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#kategori-barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('kategori-barang.index') }}',
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"entries-filter"l><"search-bar"f>>rt<"d-flex justify-content-between align-items-center"ip>',
                language: {
                    search: '', // Menghapus label default
                    searchPlaceholder: 'Cari...', // Placeholder untuk search bar
                    lengthMenu: 'Tampilkan _MENU_ data', // Label untuk jumlah entri
                    paginate: {
                        next: 'Berikutnya',
                        previous: 'Sebelumnya',
                    },
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Nomor urut
                        },
                        orderable: false,
                        searchable: false
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
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                responsive: true
            });
        });
    </script>
@endsection
