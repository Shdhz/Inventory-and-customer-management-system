@extends('layouts.admin')
@section('content')
<x-message.errors />
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-header row-cols-auto">
                <div class="col">
                    {{-- Component backurl --}}
                    <x-button.backUrl href="{{ $backUrl }}" />
                </div>
                <div class="col px-2">
                    <h2 class="page-title">{{ $title }}</h2>
                </div>
            </div>
        </div>

        <div class="card mt-3 p-2">
            <div class="card-body">
                {{-- Form Start --}}
                <form action="{{ route('kelola-invoice.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-8">
                            <img src="\dist\logo.gif" alt="logo_kaifacraft.jpg" class="img-fluid img" width="30%">
                            <p>Sentra kerajinan tangan unggulan</p>
                            <address>
                                Jl. Cikuya RT.03/07 Desa/kec. Rajapolah<br>
                                Kab.Tasikmalaya - Jawa Barat<br>
                                <span>089639152588, 081779200583</span><br>
                                <span>@kaifa_craft, @kaifacraft, @kerajinanbamburajapolah</span>
                            </address>
                        </div>

                        <div class="col-4">
                            <div class="mb-3">
                                <label for="nota_no" class="form-label">Nota No</label>
                                <input type="text" id="nota_no" name="nota_no" class="form-control"
                                    placeholder="Nomor nota akan di generate otomatis">
                            </div>
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" id="tanggal" name="tanggal" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama_pelanggan" class="form-label">Kepada</label>
                                <select id="nama_pelanggan" class="form-select" style="width: 100%;" required>
                                    <option value="">-- Pilih Nama Pelanggan --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer['id'] }}"
                                            data-produk="{{ json_encode($customer['produk'], JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE) }}">
                                            {{ $customer['nama'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="barang-container">
                        <div class="row mb-3 barang-item">
                            <div class="col">
                                <label for="Nama_Barang_1" class="form-label">Nama Barang</label>
                                <input type="text" name="Nama_Barang[]" id="Nama_Barang_1" class="form-control"
                                    placeholder="Masukkan Nama Barang" required>
                            </div>
                            <div class="col">
                                <label for="qty_1" class="form-label">Jumlah (Qty)</label>
                                <input type="number" name="qty[]" id="qty_1" class="form-control"
                                    placeholder="Jumlah" min="1" required>
                            </div>
                            <div class="col">
                                <label for="harga_1" class="form-label">Harga Satuan</label>
                                <input type="number" name="harga[]" id="harga_1" class="form-control"
                                    placeholder="Harga per item" required>
                            </div>
                        </div>
                        <hr>
                        <div class="">
                            <label for="ongkir" class="form-label">Ongkir</label>
                            <input type="number" id="ongkir" name="ongkir" class="form-control"
                                placeholder="Masukkan Ongkir" required>
                        </div>
                        <div class="mt-4">
                            <label for="dp" class="form-label">DP (Down Payment) (%)</label>
                            <input type="number" id="dp" name="dp" class="form-control" placeholder="Masukkan DP dalam persen" required>
                        </div>                        
                    </div>
                    <hr>
                    <div class="text-end">
                        <p>Sub Total: <span id="subtotal">0</span></p>
                        <p>Biaya Kirim: <span id="biaya-kirim">0</span></p>
                        <p>Down Payment (DP): <span id="dp-total">0</span></p>
                        <p class="badge text-bg-info">Status Pembayaran: <span id="status-dp"></span></p>
                        <p>Total/Sisa Belum: <span id="total-sisa">0</span></p>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="row mt-4">
                        <div class="col text-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ $backUrl }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
                {{-- Form End --}}
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#nama_pelanggan').on('change', function() {
                // Bersihkan baris barang kecuali yang pertama
                $('.barang-item:not(:first)').remove();

                // Ambil data produk dari opsi terpilih
                const selectedOption = $(this).find('option:selected');
                try {
                    const produkData = JSON.parse(selectedOption.attr('data-produk') || '[]');
                    const produkArray = Array.isArray(produkData) ? produkData : Object.values(produkData);

                    produkArray.forEach((produk, index) => {
                        if (index === 0) {
                            // Baris pertama
                            $('input[name="Nama_Barang[]"]:first').val(produk.nama_produk || produk
                                .name);
                            $('input[name="qty[]"]:first').val(produk.jumlah || produk.qty || 1);
                            $('input[name="harga[]"]:first').val(produk.harga_satuan || produk
                                .harga);
                        } else {
                            // Kloning baris pertama untuk baris tambahan
                            const newRow = $('.barang-item:first').clone();

                            newRow.find('input[name="Nama_Barang[]"]').val(produk.nama_produk ||
                                produk.name);
                            newRow.find('input[name="qty[]"]').val(produk.jumlah || produk.qty ||
                            1);
                            newRow.find('input[name="harga[]"]').val(produk.harga_satuan || produk
                                .harga);

                            // Tambahkan baris baru ke form
                            $('.barang-item:last').after(newRow);
                        }
                    });

                    // Hitung total setelah menambahkan produk
                    calculateTotals();
                } catch (error) {
                    console.error('Error parsing product data:', error, 'Raw data:', selectedOption.attr(
                        'data-produk'));
                    resetForm();
                }
            });

            // Fungsi menghitung total
            function calculateTotals() {
                let subtotal = 0;

                // Hitung subtotal berdasarkan qty dan harga
                $('.barang-item').each(function() {
                    const qty = parseInt($(this).find('input[name="qty[]"]').val()) || 0;
                    const harga = parseInt($(this).find('input[name="harga[]"]').val()) || 0;
                    subtotal += qty * harga;
                });

                // Ambil ongkir dan DP (dalam persen)
                const ongkir = parseInt($('#ongkir').val()) || 0;
                const dpPersen = parseInt($('#dp').val()) || 0; // DP dalam persen

                // Hitung DP berdasarkan persen
                const dp = (subtotal + ongkir) * (dpPersen / 100);

                // Hitung total sisa pembayaran
                const totalBelumDibayar = subtotal + ongkir - dp;

                // Update tampilan
                $('#subtotal').text(formatRupiah(subtotal));
                $('#biaya-kirim').text(formatRupiah(ongkir));
                $('#dp-total').text(formatRupiah(dp)); // Menampilkan DP dalam bentuk Rupiah
                $('#total-sisa').text(formatRupiah(totalBelumDibayar));

                // Update status pembayaran
                const statusDP = dp >= (subtotal + ongkir) ? 'Lunas' : 'Belum Lunas';
                $('#status-dp').text(statusDP);
            }


            // Fungsi format Rupiah
            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            // Fungsi reset form
            function resetForm() {
                $('.barang-item:not(:first)').remove();
                $('.barang-item input').val('');
                $('#ongkir').val('');
                $('#dp').val('');

                $('#subtotal').text('0');
                $('#biaya-kirim').text('0');
                $('#total-sisa').text('0');
                $('#status-dp').text('');
            }

            // Event listener untuk input perubahan qty, harga, ongkir, dan dp
            $(document).on('input', 'input[name="qty[]"], input[name="harga[]"], #ongkir, #dp', function() {
                calculateTotals();
            });
        });
    </script>
@endsection
