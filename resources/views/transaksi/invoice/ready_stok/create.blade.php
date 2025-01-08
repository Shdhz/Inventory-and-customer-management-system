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
                                    placeholder="Nomor nota akan di generate otomatis" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tenggat waktu</label>
                                <input type="date" id="tanggal" name="tenggat_invoice" class="form-control" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nama_pelanggan" class="form-label">Kepada</label>
                                <select id="nama_pelanggan" name="nama_pelanggan" class="form-select" style="width: 100%;"
                                    required>
                                    <option value="">-- Pilih Nama Pelanggan --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer['id'] }}"
                                            data-produk="{{ json_encode($customer['produk'], JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE) }}">
                                            {{ $customer['nama'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nama_pelanggan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="barang-container">
                        <div class="row mb-3 barang-item">
                            <div class="col">
                                <label for="Nama_Barang_1" class="form-label">Nama Barang</label>
                                <input type="text" name="Nama_Barang[]" id="Nama_Barang_1" class="form-control"
                                    placeholder="Masukkan Nama Barang" required readonly>
                                <input type="hidden" name="transaksi_detail_id[]" id="transaksi_detail_id_1">
                                @error('Nama_Barang.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="qty_1" class="form-label">Jumlah (Qty)</label>
                                <input type="number" name="qty[]" id="qty_1" class="form-control"
                                    placeholder="Jumlah" min="1" required readonly>
                                @error('qty.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col input-group">
                                <label for="harga_1" class="form-label">Harga Satuan</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="number" name="harga[]" id="harga_1" class="form-control"
                                        placeholder="Harga per item" required readonly>
                                </div>
                                @error('harga.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="mt-4 input-group">
                            <label for="ongkir" class="form-label">Ongkir</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="number" id="ongkir" name="ongkir" class="form-control"
                                    placeholder="Masukkan Ongkir" required>
                            </div>
                            @error('ongkir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-4 input-group">
                            <label for="dp" class="form-label">DP (Down Payment) (%)</label>
                            <div class="input-group">
                                <input type="number" id="dp" name="dp" class="form-control"
                                    placeholder="Masukkan DP dalam persen" required min="0" max="100">
                                <span class="input-group-text" id="basic-addon1">%</span>
                            </div>
                            @error('dp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <hr>
                    <div class="text-end">
                        <p>Biaya Kirim: <span id="biaya-kirim">0</span></p>
                        <p>Sub Total: <span id="subtotal">0</span></p>
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
            // Fungsi untuk generate nomor nota
            function generateNotaNo() {
                const date = new Date();
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');
                const notaNo = `INV/${year}${month}${day}${minutes}${seconds}`;
                $('#nota_no').val(notaNo);
            }

            // Generate nomor nota saat halaman dimuat
            generateNotaNo();

            // Validasi tanggal tidak boleh kurang dari tanggal sekarang
            $('#tanggal').on('input', function() {
                const selectedDate = new Date($(this).val());
                const currentDate = new Date();

                selectedDate.setHours(0, 0, 0, 0);
                currentDate.setHours(0, 0, 0, 0);

                const errorElement = $('#tanggal').next('.form-text');

                if (selectedDate < currentDate) {
                    if (errorElement.length === 0) {
                        const message = $(
                            '<div class="form-text text-danger">Tenggat waktu tidak boleh kurang dari tanggal sekarang.</div>'
                        );
                        $(this).after(message);
                        setTimeout(() => {
                            message.fadeOut(500, function() {
                                $(this).remove();
                            });
                        }, 2500);
                    }
                    $(this).val('');
                } else {
                    errorElement.remove();
                }
            });

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
                            $('input[name="transaksi_detail_id[]"]:first').val(produk
                                .transaksi_detail_id || produk.id
                            ); // Menambahkan transaksi_detail_id
                        } else {
                            // Kloning baris pertama untuk baris tambahan
                            const newRow = $('.barang-item:first').clone();

                            newRow.find('input[name="Nama_Barang[]"]').val(produk.nama_produk ||
                                produk.name);
                            newRow.find('input[name="qty[]"]').val(produk.jumlah || produk.qty ||
                                1);
                            newRow.find('input[name="harga[]"]').val(produk.harga_satuan || produk
                                .harga);
                            newRow.find('input[name="transaksi_detail_id[]"]').val(produk
                                .transaksi_detail_id || produk.id
                            ); // Menambahkan transaksi_detail_id

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

                const ongkir = parseInt($('#ongkir').val()) || 0;
                const dpPersen = parseInt($('#dp').val()) || 0;

                subtotal += ongkir;

                if (dpPersen > 100) {
                    dpPersen = 0;
                    $('#dp').val(dpPersen);
                }

                // Hitung DP berdasarkan persen
                const dp = (dpPersen / 100) * subtotal;

                // Hitung total yang belum dibayar
                const totalBelumDibayar = subtotal - dp;

                // Update tampilan
                $('#subtotal').text(formatRupiah(subtotal));
                $('#biaya-kirim').text(formatRupiah(ongkir));
                $('#dp-total').text(formatRupiah(dp));
                $('#total-sisa').text(formatRupiah(totalBelumDibayar));
                $('#status-dp').text(dpPersen === 100 ? 'LUNAS' : 'BELUM LUNAS');
            }

            // Format angka menjadi Rupiah
            function formatRupiah(angka) {
                return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // Event listeners untuk hitung total
            $(document).on('input', 'input[name="qty[]"], input[name="harga[]"], #ongkir, #dp', calculateTotals);

            // Reset form function
            function resetForm() {
                $('input[name="Nama_Barang[]"]:first').val('');
                $('input[name="qty[]"]:first').val(1);
                $('input[name="harga[]"]:first').val('');
                $('#ongkir').val('');
                $('#dp').val('');
                calculateTotals();
            }


            $('#dp').on('input', function() {
                let dp = parseInt($(this).val()) || 0;
                if (dp > 100) {
                    dp = 0;
                    $(this).val(dp);
                }
                calculateTotals();
            });

            calculateTotals();
        });
    </script>
@endsection
