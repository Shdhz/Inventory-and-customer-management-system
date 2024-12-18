@extends('layouts.admin')
@section('content')
    <div class="container-lg mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Laporan Penjualan</h2>
            </div>
            <div class="card-body">
                <form id="filter-form" class="mb-4">
                    <div class="row">
                        <div class="form-group col">
                            <label for="start_date">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control">
                        </div>
                        <div class="form-group col">
                            <label for="end_date">End Date:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control">
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="laporan-penjualan-table">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Tgl Keluar</th>
                                <th>Qty</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Diskon Produk</th>
                                <th>Diskon Ongkir</th>
                                <th>Ekspedisi</th>
                                <th>Pembayaran</th>
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
            var table = $('#laporan-penjualan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('laporan.penjualan') }}',
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columns: [
                    { data: null, name: 'number', orderable: false, searchable: false },
                    { data: 'nama_produk', name: 'nama_produk' },
                    { data: 'tanggal_keluar', name: 'tanggal_keluar' },
                    { data: 'qty', name: 'qty' },
                    { data: 'harga_satuan', name: 'harga_satuan' },
                    { data: 'subtotal', name: 'subtotal' },
                    { data: 'diskon_produk', name: 'diskon_produk' },
                    { data: 'diskon_ongkir', name: 'diskon_ongkir' },
                    { data: 'ekspedisi', name: 'ekspedisi' },
                    { data: 'metode_pembayaran', name: 'metode_pembayaran' },
                    { data: 'customer', name: 'customer' },
                ],
                rowCallback: function(row, data, index) {
                    var info = table.page.info();
                    var pageNumber = info.page;
                    var pageSize = info.length;
                    var rowNumber = (pageNumber * pageSize) + index + 1;
                    $('td:eq(0)', row).html(rowNumber);
                }
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            });
        });
    </script>
@endsection
