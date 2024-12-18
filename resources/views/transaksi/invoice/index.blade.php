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
                    <div class="col-auto">
                        <x-button.add-btn href="{{ route('kelola-invoice.create') }}" :button="'Tambah Invoice'" />
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-striped datatable" id="form-po" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Customer</th>
                                <th>Nama barang</th>
                                <th>Tanggal terbit</th>
                                <th>Tenggat Waktu</th>
                                <th>Tenggat Waktu</th>
                                <th>Tipe invoice</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            let table = $('#form-po').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('form-po.index') }}', // Sesuaikan dengan route index Anda
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Nomor urut otomatis
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'po_admin',
                        name: 'po_admin',
                    },
                    {
                        data: 'model',
                        name: 'model'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'ukuran',
                        name: 'ukuran'
                    },
                    {
                        data: 'bahan',
                        name: 'bahan'
                    },
                    {
                        data: 'warna',
                        name: 'warna'
                    },
                    {
                        data: 'aksesoris',
                        name: 'aksesoris'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'metode_pembayaran',
                        name: 'metode_pembayaran',
                        render: function(data) {
                            return data.charAt(0).toUpperCase() + data.slice(
                            1); // Capitalize first letter
                        }
                    },
                    {
                        data: 'status_form_po',
                        name: 'status_form_po'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"entries-filter"l><"search-bar"f>>rt<"d-flex justify-content-between align-items-center"ip>',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari data PO...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    paginate: {
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    },
                }
            });

            // Event untuk dropdown status
            $(document).on('change', '.update-status', function() {
                const formPoId = $(this).data('id');
                const newStatus = $(this).val();

                $.ajax({
                    url: '{{ route('form-po.update-status', ':id') }}'.replace(':id', formPoId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            // Reload tabel jika berhasil
                            table.ajax.reload();
                        } else {
                            alert('Gagal memperbarui status.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat memperbarui status.');
                    }
                });
            });
        });
    </script> --}}
@endsection