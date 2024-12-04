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
                    <x-button.add-btn :button="$button" href="" />
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive overflow-auto">
                    <table class="table table-striped datatable" id="barang-rusak" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Masuk</th>
                                <th>Kategori barang</th>
                                <th>Nama barang</th>
                                <th>Jumlah masuk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <script>
        $('#barang-rusak').DataTable({
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
                    data: 'kategori_id',
                    name: 'kategori_id'
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
    </script> --}}
@endsection