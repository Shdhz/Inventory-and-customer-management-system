@extends('layouts.admin')
@section('content')
    <div class="container-lg mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Laporan Penjualan</h2>
            </div>
            <div class="card-body">
                <form id="filter-form" class="mb-4" method="GET" action="{{ route('laporan.penjualan') }}">
                    <div class="row">
                        <div class="form-group col">
                            <label for="start_date">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="form-group col">
                            <label for="end_date">End Date:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control"
                                value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary w-100 me-2">Filter</button>
                            <button id="export-pdf-btn" type="button" class="btn btn-danger w-100">
                                Export PDF
                            </button>
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
            // Konfigurasi DataTable
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
                columns: [{
                        data: null,
                        name: 'number',
                        orderable: false,
                        searchable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'nama_produk',
                        name: 'nama_produk'
                    },
                    {
                        data: 'tanggal_keluar',
                        name: 'tanggal_keluar'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'harga_satuan',
                        name: 'harga_satuan',
                        render: function(data, type, row) {
                            return formatRupiah(data, 'Rp ');
                        }
                    },
                    {
                        data: 'subtotal',
                        name: 'subtotal',
                        render: function(data, type, row) {
                            return formatRupiah(data, 'Rp ');
                        }
                    },
                    {
                        data: 'diskon_produk',
                        name: 'diskon_produk'
                    },
                    {
                        data: 'diskon_ongkir',
                        name: 'diskon_ongkir'
                    },
                    {
                        data: 'ekspedisi',
                        name: 'ekspedisi'
                    },
                    {
                        data: 'metode_pembayaran',
                        name: 'metode_pembayaran'
                    },
                    {
                        data: 'customer',
                        name: 'customer'
                    },
                ],
                rowCallback: function(row, data, index) {
                    var info = table.page.info();
                    var rowNumber = info.start + index + 1;
                    $('td:eq(0)', row).html(rowNumber);
                }
            });

            // Fungsi format rupiah
            function formatRupiah(number, prefix) {
                if (!number) return prefix + '0';
                return prefix + parseInt(number).toLocaleString('id-ID');
            }

            // Submit form filter
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                if (!$('#start_date').val() || !$('#end_date').val()) {
                    alert('Mohon isi kedua tanggal.');
                    return;
                }
                table.draw();
            });

            // Tombol Export PDF
            $('#export-pdf-btn').on('click', function() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                if (!startDate || !endDate) {
                    alert('Mohon isi kedua tanggal sebelum export PDF.');
                    return;
                }

                const url = '{{ route('laporan.penjualan.pdf') }}?start_date=' + startDate + '&end_date=' +
                    endDate;
                window.open(url, '_blank');
            });
        });
    </script>
@endsection


