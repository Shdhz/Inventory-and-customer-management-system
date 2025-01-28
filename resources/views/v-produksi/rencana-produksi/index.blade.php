@extends('layouts.produksi')

@section('content')
    <x-message.success />
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">{{ $title }}</h2>
                    </div>
                    @role('produksi')
                        <x-button.add-btn :button="$button" href="{{ route('rencana-produksi.create') }}" />
                    @endrole
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable" id="kategori-barang" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Po admin</th>
                                <th>Prioritas PO</th>
                                <th>Nama barang</th>
                                <th>Model</th>
                                <th>Nama Pengrajin</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Status</th>
                                @role('produksi')
                                <th>Aksi</th>
                                @endrole
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#kategori-barang').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('rencana-produksi.index') }}',
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'po_admin',
                    name: 'po_admin'
                },
                {
                    data: 'prioritas',
                    name: 'prioritas'
                },
                {
                    data: 'nama_barang',
                    name: 'nama_barang'
                },
                {
                    data: 'model',
                    name: 'model'
                },
                {
                    data: 'nama_pengrajin',
                    name: 'nama_pengrajin'
                },
                {
                    data: 'mulai_produksi',
                    name: 'mulai_produksi'
                },
                {
                    data: 'berakhir_produksi',
                    name: 'berakhir_produksi'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                @role('produksi')
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
                @endrole
            ],
            responsive: true
        });
    </script>
    <x-button.confirmdelete />
@endsection
