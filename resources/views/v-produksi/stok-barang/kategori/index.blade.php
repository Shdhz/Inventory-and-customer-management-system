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
                    <x-button.add-btn :button="$button" href="{{ route('kategori-barang.create') }}" />
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
                    search: '',
                    searchPlaceholder: 'Cari...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    paginate: {
                        next: 'Berikutnya',
                        previous: 'Sebelumnya',
                    },
                },
                columnDefs: [
                    {
                    targets: '_all',
                    className: 'text-start',
                    }, 
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
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
