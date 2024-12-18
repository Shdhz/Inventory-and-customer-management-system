@extends('layouts.admin')
@section('content')
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
                <form action="" method="POST">
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
                                    placeholder="Masukkan nomor nota" required>
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
                            <label for="dp" class="form-label">DP (Down Payment)</label>
                            <input type="number" id="dp" name="dp" class="form-control"
                                placeholder="Masukkan DP" required>
                        </div>
                    </div>
                    <hr>
                    <div class="text-end">
                        <p>Sub Total: <span id="subtotal">0</span></p>
                        <p>Biaya Kirim: <span id="biaya-kirim">0</span></p>
                        <p>Down Payment (DP): <span id="dp-total">0</span></p>
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
                    // Clear existing rows
                    $('.barang-item:not(:first)').remove();

                    // Get selected option
                    const selectedOption = $(this).find('option:selected');

                    try {
                        // Parse product data with fallback
                        const produkData = JSON.parse(selectedOption.attr('data-produk') || '[]');

                        // Ensure produkData is an array
                        const produkArray = Array.isArray(produkData) ? produkData :
                            (typeof produkData === 'object' ? Object.values(produkData) : []);

                        // Populate product rows
                        produkArray.forEach((produk, index) => {
                            // For first row
                            if (index === 0) {
                                $('input[name="Nama_Barang[]"]:first').val(produk.nama_produk || produk
                                    .name);
                                $('input[name="qty[]"]:first').val(produk.jumlah || produk.qty || 1);
                                $('input[name="harga[]"]:first').val(produk.harga_satuan || produk
                                    .harga_satuan);
                            }
                            // For additional rows
                            else {
                                // Clone the first row
                                const newRow = $('.barang-item:first').clone();

                                // Reset values in cloned row
                                newRow.find('input[name="Nama_Barang[]"]').val(produk.nama_produk ||
                                    produk.name);
                                newRow.find('input[name="qty[]"]').val(produk.jumlah || produk.qty ||
                                1);
                                newRow.find('input[name="harga[]"]').val(produk.harga_satuan || produk
                                    .harga_satuan);

                                // Append to the container
                                $('.barang-item:last').after(newRow);
                            }
                        });

                        // Calculate totals
                        calculateTotals();
                    } catch (error) {
                        console.error('Error parsing product data:', error,
                            'Raw data:', selectedOption.attr('data-produk'));
                        // Reset form or show error message
                        resetForm();
                    }
                });

                // Function to calculate totals
                function calculateTotals() {
                    let subtotal = 0;

                    // Calculate subtotal
                    $('.barang-item').each(function() {
                        const qty = $(this).find('input[name="qty[]"]').val() || 0;
                        const harga = $(this).find('input[name="harga[]"]').val() || 0;
                        subtotal += qty * harga;
                    });

                    // Get ongkir and dp values
                    const ongkir = $('#ongkir').val() || 0;
                    const dp = $('#dp').val() || 0;

                    // Calculate total and remaining amount
                    const totalBelumDibayar = subtotal + parseInt(ongkir) - parseInt(dp);

                    // Update display
                    $('#subtotal').text(formatRupiah(subtotal));
                    $('#biaya-kirim').text(formatRupiah(ongkir));
                    $('#dp-total').text(formatPersen(dp, subtotal)); // Format DP as percentage
                    $('#total-sisa').text(formatRupiah(totalBelumDibayar));
                }

                // Function to format number to Rupiah
                function formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(angka);
                }

                // Function to format number to percentage (e.g., 20%)
                function formatPersen(dp, subtotal) {
                    if (subtotal === 0) return '0%'; // Avoid dividing by zero
                    const persen = (dp / subtotal) * 100;
                    return `${persen.toFixed(2)}%`;
                }

                // Reset form function
                function resetForm() {
                    $('.barang-item:not(:first)').remove();
                    $('.barang-item input').val('');
                    $('#ongkir').val('');
                    $('#dp').val('');

                    $('#subtotal').text('0');
                    $('#biaya-kirim').text('0');
                    $('#dp-total').text('0');
                    $('#total-sisa').text('0');
                }

                // Attach calculation to input changes
                $(document).on('input', 'input[name="qty[]"], input[name="harga[]"], #ongkir, #dp', function() {
                    calculateTotals();
                });
            });
    </script>
@endsection
