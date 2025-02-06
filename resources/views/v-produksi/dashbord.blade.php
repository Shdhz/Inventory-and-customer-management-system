@extends('layouts.produksi')
@section('dashboard', 'dashboard-homepage')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Dashboard Produksi
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <span class="d-none d-sm-inline">
                            <a href="{{ route('stok-barang.create') }}" class="btn">
                                Tambah Stok Barang
                            </a>
                        </span>
                        <a href="{{ route('rencana-produksi.create') }}" class="btn btn-primary d-none d-sm-inline-block"
                            data-bs-toggle="modal" data-bs-target="#modal-report">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Tambah Rencana Produksi
                        </a>
                        <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal"
                            data-bs-target="#modal-report" aria-label="Create new report">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-auto mt-5">
                <div class="row row-cards bg-white p-3">
                    <h2>Form Po yang aktif</h2>
                    <table id="formPoTable" class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama PO</th>
                                <th>Nama Customer</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($formPoActive as $po)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $po->keterangan }}</td>
                                    <td>{{ $po->customerOrder->draftCustomer->Nama ?? 'Tidak Ada' }}</td>
                                    <td>
                                        <span class="badge text-white {{ $po->status_form_po == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $po->status_form_po == 1 ? 'Aktif' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $po->created_at->format('d F Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#formPoTable').DataTable();
        });
    </script>
@endsection
