@extends('layouts.admin')
@section('dashboard', 'dashboard-draft-customer')
@section('content')
    <x-message.success />
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Draft Customer All Data</h2>
                    </div>
                    <x-button.add-btn :button="$button" href="{{ route('draft-customer.create') }}" />
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable" id="draft-customer" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Dikelola</th>
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
                            data: 'admin_name',
                            name: 'admin_name'
                        },
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
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    responsive: true
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
