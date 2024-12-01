@extends('layouts.admin')
@section('content')
<x-message.success />
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Order Customer All Data</h2>
                    </div>
                    <div class="col-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ route('order-customer.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" />
                                </svg>
                                Tambah Order Customer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable" id="order-customer" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama customer</th>
                                <th>Sumber Customer</th>
                                <th>Tipe Order</th>
                                <th>Jenis Order</th>
                                <th>keterangan</th>
                                <th>Dibuat pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <script>
        $('#order-customer').DataTable({
        processing: true,
        serverSide: true,
        ajax: { 
            url: '{{ route('order-customer.index') }}',
            error: function(xhr, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
        }
        },
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
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'Nama', name: 'Nama' },
            { data: 'sumber', name: 'sumber' },
            { data: 'tipe_order', name: 'tipe_order' },
            { data: 'jenis_order', name: 'jenis_order' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'updated_at', name: 'updated_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        });
        </script>
    @endsection
