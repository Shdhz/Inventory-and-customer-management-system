@extends('layouts.admin')

@section('content')
    <x-message.success />
    <div class="container-lg mt-4">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">{{ $title }}</h2>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-striped datatable" id="invoice-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Nota</th>
                                <th>Nama Customer</th>
                                <th>Item dipilih</th>
                                <th>Subtotal</th>
                                <th>Ongkir</th>
                                <th>Total</th>
                                <th>Down payment(Dp)</th>
                                <th>Status Pembayaran</th>
                                <th>Tenggat Waktu</th>
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
            $('#invoice-table').DataTable({
                processing: true,
                serverSide: true,
                debug: true,
                ajax: '{{ route('kelola-invoice.index') }}',
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Nomor urut otomatis
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nota_no',
                        name: 'nota_no'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'nama_produk',
                        name: 'nama_produk'
                    },
                    {
                        data: 'subtotal',
                        name: 'subtotal'
                    },
                    {
                        data: 'ongkir',
                        name: 'ongkir'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'dp',
                        name: 'dp'
                    },
                    {
                        data: 'status_pembayaran',
                        name: 'status_pembayaran'
                    },
                    {
                        data: 'tenggat_invoice',
                        name: 'tenggat_invoice'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"entries-filter"l><"search-bar"f>>rt<"d-flex justify-content-between align-items-center"ip>',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari invoice...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    paginate: {
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    },
                }
            });
        });
    </script>
@endsection
