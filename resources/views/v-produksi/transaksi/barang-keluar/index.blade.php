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
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive overflow-auto">
                    <table class="table table-striped datatable" id="barang-keluar" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Keluar</th>
                                <th>Kategori Barang</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Keluar</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#barang-keluar').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('barang-keluar.index') }}',
                    type: 'GET',
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
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'tanggal_keluar', name: 'tanggal_keluar' },
                    { data: 'kategori_barang', name: 'kategori_barang' },
                    { data: 'nama_barang', name: 'nama_barang' },
                    { data: 'jumlah_keluar', name: 'jumlah_keluar' },
                ],
            });
        });
    </script>
@endsection