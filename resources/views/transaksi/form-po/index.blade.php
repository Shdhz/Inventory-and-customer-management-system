@extends('layouts.admin')

@section('content')
    <x-message.success />
    <div class="container-lg mt-4">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Data form po</h2>
                    </div>
                    @role('admin')
                    <div class="col-auto">
                        <x-button.invoice-btn href="{{ route('form-po-invoice.create') }}" :btn_invoice="'Tambah Invoice'" />
                    </div>
                    <div class="col-auto">
                        <x-button.add-btn href="{{ route('form-po.create') }}" :button="'Tambah Form Po'" />
                    </div>
                    @endrole
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-striped datatable" id="form-po" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Po admin</th>
                                <th>Nama customer</th>
                                <th>Model</th>
                                <th>Qty</th>
                                <th>Ukuran</th>
                                <th>Bahan</th>
                                <th>Warna</th>
                                <th>Aksesoris</th>
                                <th>Keterangan</th>
                                <th>Metode pembayaran</th>
                                <th>status form</th>
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
                        data: 'nama_customer',
                        name: 'nama_customer',
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
