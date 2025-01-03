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
                <ul class="nav nav-tabs mt-4" id="invoiceTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="form-po-tab" data-bs-toggle="tab" data-bs-target="#form-po"
                        type="button" role="tab" aria-controls="form-po" aria-selected="true">Invoice Form
                        PO</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail"
                            type="button" role="tab" aria-controls="detail" aria-selected="false">Invoice
                            Ready stock</button>
                    </li>
                </ul>
                <div class="tab-content" id="invoiceTabsContent">
                    <!-- Invoice Form PO -->
                    <div class="tab-pane fade show active" id="form-po" role="tabpanel" aria-labelledby="form-po-tab">
                        <div class="table-responsive mt-4">
                            <table class="table table-striped datatable" id="form-po-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Invoice</th>
                                        <th>Nama Customer</th>
                                        <th>Item dipilih</th>
                                        <th>Subtotal</th>
                                        <th>Ongkir</th>
                                        <th>Total</th>
                                        <th>Down payment (Dp)</th>
                                        <th>Status Pembayaran</th>
                                        <th>Tenggat Waktu</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- Invoice Detail -->
                    <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                        <div class="table-responsive mt-4">
                            <table class="table table-striped datatable" id="detail-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Invoice</th>
                                        <th>Nama Customer</th>
                                        <th>Item dipilih</th>
                                        <th>Subtotal</th>
                                        <th>Ongkir</th>
                                        <th>Total</th>
                                        <th>Down payment (Dp)</th>
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
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // DataTable untuk Invoice Form PO
            $('#form-po-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('form-po-invoice.index') }}',
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
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

            // DataTable untuk Invoice Detail
            $('#detail-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('kelola-invoice.index') }}',
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
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

        function confirmDelete(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "DELETE"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Dihapus!',
                                'Data berhasil dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endsection
