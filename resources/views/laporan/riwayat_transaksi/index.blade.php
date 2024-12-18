@extends('layouts.admin')

@section('content')
    <div class="container-lg mt-4">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Riwayat Transaksi</h2>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('riwayat.transaksi.pdf', ['search' => request()->get('search')['value'] ?? '']) }}"
                            class="btn btn-danger">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-pdf">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                    <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                                    <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6" />
                                    <path d="M17 18h2" />
                                    <path d="M20 15h-3v6" />
                                    <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" />
                                </svg>
                            </span>Export PDF</a>
                        {{-- <a href="{{ route('laporan.penjualan.excel') }}" class="btn btn-success">Export Excel</a> --}}
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-striped datatable" id="laporan-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                                <th>Tanggal transaksi</th>
                                <th>Customer</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#laporan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('riwayat.transaksi') }}',
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Nomor urut otomatis
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_barang',
                        name: 'nama_barang'
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                        render: function(data) {
                            return data ? `${data.toLocaleString('id-ID')} pcs` : '-';
                        }
                    },
                    {
                        data: 'harga_satuan',
                        name: 'harga_satuan'
                    },
                    {
                        data: 'subtotal',
                        name: 'subtotal'
                    },
                    {
                        data: 'tanggal_transaksi',
                        name: 'tanggal_transaksi'
                    },
                    {
                        data: 'customer',
                        name: 'customer'
                    }
                ],
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
            });
        });
    </script>
@endsection
