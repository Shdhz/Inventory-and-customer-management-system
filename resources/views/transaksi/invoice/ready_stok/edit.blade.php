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
                <form action="{{ route('kelola-invoice.update', $invoice->invoice_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-8">
                            <img src="\dist\logo_kaifacraftgroup.png" alt="logo_kaifacraft.jpg" class="img-fluid img"
                                width="35%">
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
                                </svg>{{ $Instagram->no_hp }}<br>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-instagram"
                                    width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 8a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                    <path d="M16.5 7.5v.01" />
                                </svg>
                                @foreach ($Instagram->instagramForAdmin as $instagram)
                                    {{ $instagram->nama_instagram }}
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </address>
                        </div>

                        <div class="col-4">
                            <div class="mb-3">
                                <label for="nota_no" class="form-label">Nota No</label>
                                <input type="text" id="nota_no" name="nota_no" class="form-control"
                                    value="{{ old('nota_no') }}" placeholder="Nomor nota akan di generate otomatis"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tenggat waktu</label>
                                <input type="date" id="tanggal" name="tenggat_invoice" class="form-control"
                                    value="{{ old('tenggat_invoice', $invoice->tenggat_invoice) }}" required>
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
                                            data-produk="{{ json_encode($customer['produk'], JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE) }}"
                                            {{ old('nama_pelanggan', $invoice->invoiceDetails->first()->transaksiDetail->transaksi->customerOrder->draftCustomer->draft_customers_id ?? '') == $customer['id'] ? 'selected' : '' }}>
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
                        @foreach ($invoice->invoiceDetails as $index => $detail)
                            <div class="row mb-3 barang-item">
                                <div class="col">
                                    <label for="Nama_Barang_{{ $index }}" class="form-label">Nama Barang</label>
                                    <input type="text" name="Nama_Barang[]" id="Nama_Barang_{{ $index }}"
                                        class="form-control"
                                        value="{{ old("Nama_Barang.$index", $detail->transaksiDetail->stok->nama_produk ?? 'Unnamed Product') }}"
                                        placeholder="Masukkan Nama Barang" readonly>
                                    <input type="hidden" name="transaksi_detail_id[]"
                                        value="{{ $detail->transaksiDetail->id_transaksi_detail ?? '' }}">
                                </div>
                                <div class="col">
                                    <label for="qty_{{ $index }}" class="form-label">Jumlah (Qty)</label>
                                    <input type="number" name="qty[]" id="qty_{{ $index }}" class="form-control"
                                        value="{{ old("qty.$index", $detail->transaksiDetail->qty ?? 0) }}"
                                        placeholder="Jumlah" readonly>
                                </div>
                                <div class="col">
                                    <label for="harga_{{ $index }}" class="form-label">Harga Satuan</label>
                                    <input type="number" name="harga[]" id="harga_{{ $index }}"
                                        class="form-control"
                                        value="{{ old("harga.$index", floatval($detail->transaksiDetail->harga_satuan)) }}"
                                        placeholder="Harga per item" readonly>
                                </div>
                                <div class="col">
                                    <label for="subtotal_{{ $index }}" class="form-label">Subtotal</label>
                                    <input type="number" name="subtotal[]" id="subtotal_{{ $index }}"
                                        class="form-control"
                                        value="{{ old("subtotal.$index", floatval($detail->transaksiDetail->subtotal ?? 0)) }}"
                                        placeholder="Subtotal" readonly>
                                </div>
                            </div>
                        @endforeach

                        <hr>
                        <div class="mt-4 input-group">
                            <label for="ongkir" class="form-label">Ongkir</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="number" id="ongkir" name="ongkir" class="form-control"
                                    value="{{ old('ongkir', floatval($invoice->ongkir)) }}" placeholder="Masukkan Ongkir" required>
                            </div>
                            @error('ongkir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-4 input-group">
                            <label for="dp" class="form-label">DP (Down Payment) (%)</label>
                            <div class="input-group">
                                <input type="number" id="dp" name="dp" class="form-control"
                                    value="{{ old('dp', number_format($dpPersen, 2)) }}" placeholder="Masukkan DP dalam persen" required
                                    min="0" max="100" step="0.01">
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
                let dpPersen = parseFloat($('#dp').val().replace(',', '.')) || 0;

                subtotal += ongkir;

                // Validasi input DP persen
                if (dpPersen > 100) {
                    $('#dp').val(100);
                }

                // Hitung nominal DP
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
                return 'Rp ' + angka.toLocaleString('id-ID');
            }

            // Event listener untuk input yang memengaruhi total
            $('input[name="qty[]"], input[name="harga[]"], #ongkir, #dp').on('input', function() {
                calculateTotals();
            });

            // Reset form function
            function resetForm() {
                $('input[name="Nama_Barang[]"]:first').val('');
                $('input[name="qty[]"]:first').val(1);
                $('input[name="harga[]"]:first').val('');
                $('#ongkir').val('');
                $('#dp').val('');
                calculateTotals();
            }

            calculateTotals();
        });
    </script>
@endsection
