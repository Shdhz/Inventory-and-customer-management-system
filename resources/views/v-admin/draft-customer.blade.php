@extends('layouts.admin')
@section('dashboard', 'dashboard-draft-customer')
@section('content')
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Draft Customer All Data</h2>
                    </div>
                    <div class="col-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ route('draft-customer.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" />
                                </svg>
                                Tambah Draft Customer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable" id="draft-customer" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama customer</th>
                                <th>No Hp</th>
                                <th>Email</th>
                                <th>Provinsi</th>
                                <th>Kota</th>
                                <th>Alamat lengkap</th>
                                <th>Sumber customer</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#draft-customer').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('draft-customer.index') }}',
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
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        }, // Untuk nomor urut
                        {
                            data: 'Nama',
                            name: 'Nama'
                        },
                        {
                            data: 'no_hp',
                            name: 'no_hp'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'provinsi',
                            name: 'provinsi'
                        },
                        {
                            data: 'kota',
                            name: 'kota'
                        },
                        {
                            data: 'alamat_lengkap',
                            name: 'alamat_lengkap'
                        },
                        {
                            data: 'sumber',
                            name: 'sumber'
                        },
                        {
                            data: null,
                            name: 'actions',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return `<x-button.action-btn />`;
                            }
                        }
                    ],
                    responsive: true
                });
            });
        </script>
    @endsection
