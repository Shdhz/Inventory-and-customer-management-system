@extends('layouts.admin')
@section('content')
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                {{-- Bagian Kiri --}}
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        {{-- Komponen Backurl --}}
                        <x-button.backUrl href="{{ $backUrl }}" />
                    </div>
                    <h2 class="page-title mb-0">{{ $title }}</h2>
                </div>

                {{-- Bagian Kanan --}}
                <div>
                    {{-- Tombol Download PDF --}}
                    <a href="{{ route('invoice.downloadPdf', $invoice->invoice_id) }}" class="btn btn-danger">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-pdf">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                                <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6" />
                                <path d="M17 18h2" />
                                <path d="M20 15h-3v6" />
                                <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" />
                            </svg>
                        </span> Download Invoice
                    </a>
                </div>
            </div>
        </div>
        <div class="card mt-3 p-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    {{-- Informasi Kaifacraft --}}
                    <div class="col-8">
                        <img src="\dist\logo.gif" alt="logo_kaifacraft.jpg" class="img-fluid mb-2" width="30%">
                        <p>Sentra kerajinan tangan unggulan</p>
                        <address>
                            Jl. Cikuya RT.03/07 Desa/kec. Rajapolah<br>
                            Kab. Tasikmalaya - Jawa Barat<br>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-whatsapp"
                                width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
                                <path
                                    d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
                            </svg> 089639152588, 081779200583<br>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-instagram"
                                width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 8a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                                <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                <path d="M16.5 7.5v.01" />
                            </svg> @kaifa_craft, @kaifacraft, @kerajinanbamburajapolah
                        </address>
                    </div>
                    <div class="col-4 text-end">
                        <div class="mb-3">
                            <p><strong>Tenggat waktu:</strong>
                                {{ \Carbon\Carbon::parse($invoice->tenggat_invoice)->format('d F Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <p><strong>Nota No:</strong> {{ $invoice->nota_no }}</p>
                        </div>
                        <div class="mb-3">
                            <p><strong>Kepada:</strong>
                                {{-- {{ $customerOrder->first() }} --}}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Tabel Detail Produk --}}
                <table class="table table-bordered mt-4">
                    <thead class="table-secondary">
                        <tr>
                            <th>Nama Barang</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoiceFormPo as $detail)
                            <tr>
                                <td>{{ $detail->formPo->keterangan ?? 'Tidak ada data' }}</td>
                                <td class="text-center">{{ $detail->formPo->qty ?? 0 }}</td>
                                <td class="text-end">
                                    {{ 'Rp ' . number_format($detail->invoice->harga_satuan ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-end">
                                    {{ 'Rp ' . number_format($detail->invoice->subtotal ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Rincian Total --}}
                <div class="row mt-4">
                    <div class="col-8">
                        <p>Pembayaran via transfer:</p>
                        <p>
                            <span class="me-2">
                                <img src="/BCA.svg" alt="BCA Logo">
                            </span> : 6320 3530 82 <span class="ms-3">
                        </p>
                        <p>
                            <span class="me-2">
                                <img src="/BRI.svg" alt="BRI Logo">
                            </span>: 3466-01-035685-53-3
                        </p>
                        <p>a.n. <strong>Sandi Susandi</strong></p>
                    </div>
                    <div class="col-4 text-end">
                        <p><strong>Biaya Kirim:</strong> {{ number_format($invoice->ongkir ?? 0, 0, ',', '.') }}</p>
                        <p><strong>Sub Total:</strong> {{ number_format($invoice->subtotal ?? 0, 0, ',', '.') }}</p>
                        <p><strong>Down Payment (DP):</strong>
                            {{ number_format($invoice->down_payment ?? 0, 0, ',', '.') }}</p>
                        <p><strong>Total/sisa belum:</strong> {{ number_format($invoice->total ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                <p class="text-end mt-4">Hormat Kami,</p>
                <p class="text-end"><strong>Kaifacraft</strong></p>
            </div>
            <hr>
            <div class="mt-4">
                <h3>Members :</h3>
                <img src="\dist\members.svg" alt="" width="100%">
            </div>
        </div>
    </div>
@endsection
