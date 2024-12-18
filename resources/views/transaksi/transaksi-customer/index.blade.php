@extends('layouts.admin')

@section('content')
<x-message.success />
    <div class="container-lg mt-4">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Data Transaksi Customer</h2>
                    </div>
                    <div class="col-auto">
                        <x-button.invoice-btn href="{{ route('kelola-invoice.create') }}" :btn_invoice="'Tambah Invoice'" />
                    </div>
                    <div class="col-auto">
                        <x-button.add-btn href="{{ route('transaksi-customer.create') }}" :button="'Tambah Transaksi'" />
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-striped datatable" id="transaksi-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Customer</th>
                                <th>Tanggal transaksi</th>
                                {{-- <th>Nama produk</th>
                                <th>Jumlah Item</th> --}}
                                <th>Item dipilih</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                                <th>Diskon</th>
                                <th>Grand Total</th>
                                <th>ekspedisi</th>
                                <th>Metode Pembayaran</th>
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
            $('#transaksi-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('transaksi-customer.index') }}',
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
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        render: function(data) {
                            var date = new Date(data);
                            var day = ("0" + date.getDate()).slice(-2);
                            var month = ("0" + (date.getMonth() + 1)).slice(-2);
                            var year = date.getFullYear();
                            return day + '-' + month + '-' + year;
                        }
                    },
                    {
                        data: 'item_pilih',
                        name: 'item_pilih',
                    },
                    {
                        data: 'harga_satuan',
                        name: 'harga_satuan',
                        render: function(data) {
                            return data ? `Rp ${data.toLocaleString('id-ID')}` : '-';
                        }
                    },
                    {
                        data: 'total_harga',
                        name: 'total_harga',
                        render: function(data) {
                            return data ? `Rp ${data.toLocaleString('id-ID')}` : '-';
                        }
                    },
                    {
                        data: 'diskon',
                        name: 'diskon',
                        render: function(data) {
                            return data ? `${parseFloat(data).toLocaleString('id-ID')}%` : '-';
                        }
                    },
                    {
                        data: 'grand_total',
                        name: 'grand_total',
                        render: function(data) {
                            return data ? `Rp ${data.toLocaleString('id-ID')}` : '-';
                        }
                    },
                    {
                        data: 'ekspedisi',
                        name: 'ekspedisi',
                    },
                    {
                        data: 'metode_pembayaran',
                        name: 'metode_pembayaran',
                        render: function(data) {
                            return `<span class="badge bg-primary text-white">${data}</span>`;
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                    }
                ],
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"entries-filter"l><"search-bar"f>>rt<"d-flex justify-content-between align-items-center"ip>',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari transaksi...',
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
