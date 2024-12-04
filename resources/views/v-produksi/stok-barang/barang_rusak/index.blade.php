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
                    <x-button.add-btn :button="$button" href="{{ route('barang-rusak.create') }}" />
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive overflow-auto">
                    <table class="table table-striped datatable" id="stok-barang" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama barang</th>
                                <th>Jumlah barang rusak</th>
                                <th>Diedit pada</th>
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
                url: '{{ route('barang-rusak.index') }}',
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
                    data: 'nama_produk',
                    name: 'nama_produk'
                },
                {
                    data: 'jumlah_barang_rusak',
                    name: 'jumlah_barang_rusak'
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