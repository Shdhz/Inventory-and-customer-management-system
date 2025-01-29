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
                <form action="{{ route('form-po-invoice.update', $invoice->invoice_id) }}" method="POST">
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
                                    value="{{ $invoice->nota_no }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tenggat waktu <span
                                        class="text-danger">*</span></label>
                                <input type="date" id="tanggal" name="tenggat_invoice" class="form-control"
                                    value="{{ $invoice->tenggat_invoice }}" required>
                                @error('tanggal')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nama_pelanggan" class="form-label">Kepada <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control"
                                    value="{{ $namaPelanggan }}" required readonly>
                                @error('nama_pelanggan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Invoice pre order hanya untuk pre order yang sudah aktif.</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    @foreach ($detail_produk as $index => $detail)
                        <div class="row mb-3 barang-item">
                            <div class="col">
                                <label for="Nama_Barang_{{ $index + 1 }}" class="form-label">Nama Barang <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="keterangan[]" id="keterangan{{ $index + 1 }}"
                                    class="form-control" placeholder="Masukkan Nama Barang"
                                    value="{{ $detail['keterangan'] }}" required readonly>
                                <input type="hidden" name="form_po_id[]" id="form_po_id_{{ $index + 1 }}"
                                    value="{{ $detail['form_po_id'] }}">
                                @error('keterangan.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="qty_{{ $index + 1 }}" class="form-label">Jumlah (Qty) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="qty[]" id="qty_{{ $index + 1 }}" class="form-control"
                                    placeholder="Jumlah" value="{{ $detail['qty'] }}" min="1" required readonly>
                                @error('qty.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col input-group mb-3">
                                <label for="harga_satuan{{ $index + 1 }}" class="form-label">Harga Satuan <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="number" name="harga_satuan[]" id="harga_satuan{{ $index + 1 }}"
                                        class="form-control harga-satuan" placeholder="Masukkan harga per item"
                                        value="{{ round($detail['harga_satuan']) }}" required>
                                </div>
                                @error('harga.*')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                    <hr>
                    <div class="mt-4 input-group">
                        <label for="ongkir" class="form-label">Ongkir <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">Rp</span>
                            <input type="number" id="ongkir" name="ongkir" class="form-control"
                                placeholder="Masukkan Ongkir" value="{{ round($invoice->ongkir) }}" required>
                        </div>
                        @error('ongkir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-4 input-group">
                        <label for="dp" class="form-label">DP (Down Payment) (%) <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" id="dp" name="dp" class="form-control"
                                placeholder="Masukkan DP dalam persen" value="{{ $dpPersen }}" required
                                min="0" max="100">
                            <span class="input-group-text" id="basic-addon1">%</span>
                        </div>
                        @error('dp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
            </div>
            <hr>
            <div class="text-end">
                <p>Biaya Kirim: <span id="biaya-kirim">{{ number_format($invoice->ongkir, 0, ',', '.') }}</span>
                </p>
                <p>Sub Total: <span id="subtotal">{{ number_format($invoice->subtotal, 0, ',', '.') }}</span></p>
                <p>Down Payment (DP): <span id="dp-total">{{ number_format($invoice->down_payment, 0, ',', '.') }}</span>
                </p>
                <p class="badge text-bg-info">Status Pembayaran: <span id="status-dp">
                        {{ $invoice->dp == 100 ? 'LUNAS' : 'BELUM LUNAS' }}</span></p>
                <p>Total/Sisa Belum: <span id="total-sisa">{{ number_format($invoice->total, 0, ',', '.') }}</span>
                </p>
            </div>

            {{-- Tombol Submit --}}
            <div class="row mt-4">
                <div class="col text-end">
                    <button type="submit" class="btn btn-primary">Update</button>
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
                const selectedOption = $(this).find('option:selected');
                const barangData = JSON.parse(selectedOption.attr('data-barang') || '[]');
                const formPoId = selectedOption.val();

                // Reset barang container
                $('.barang-item:not(:first)').remove();
                const firstRow = $('.barang-item:first');
                firstRow.find('input[name="Nama_Barang[]"]').val('');
                firstRow.find('input[name="qty[]"]').val('');
                firstRow.find('input[name="harga[]"]').val('');
                firstRow.find('input[name="form_po_id[]"]').val(formPoId);

                barangData.forEach((item, index) => {
                    const newRow = index === 0 ? firstRow : firstRow.clone();

                    // Set values for the inputs in the row
                    newRow.find('input[name="Nama_Barang[]"]').val(item.keterangan || '-');
                    newRow.find('input[name="qty[]"]').val(item.qty || 1);
                    newRow.find('input[name="harga[]"]').val('');
                    newRow.find('input[name="form_po_id[]"]').val(item
                        .form_po_id); // Update form_po_id in each row

                    // Update the IDs for the new row to ensure uniqueness
                    const rowIndex = index + 1; // Adding 1 to the index to start from 1
                    newRow.find('input[name="Nama_Barang[]"]').attr('id', 'Nama_Barang_' +
                        rowIndex);
                    newRow.find('input[name="qty[]"]').attr('id', 'qty_' + rowIndex);
                    newRow.find('input[name="harga[]"]').attr('id', 'harga_' + rowIndex);
                    newRow.find('input[name="form_po_id[]"]').attr('id', 'form_po_id_' + rowIndex);

                    // Append the new row to the container
                    if (index === 0) {
                        // For the first item, update the first row (no clone needed)
                        firstRow.replaceWith(newRow);
                    } else {
                        // For subsequent items, append the cloned row
                        $('.barang-item:last').after(newRow);
                    }
                });
            });

            function formatRupiah(angka) {
                return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // Fungsi untuk format angka dengan pemisah ribuan
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            // Fungsi validasi input angka
            function validateNumberInput(element) {
                let value = parseFloat(element.val().replace(/[^\d]/g, '')) || 0;
                if (value < 0) {
                    value = 0;
                }
                element.val(value);
                return value;
            }

            function calculateTotals() {
                let subtotal = 0;

                // Hitung subtotal berdasarkan qty dan harga
                $('.barang-item').each(function() {
                    const qty = parseFloat($(this).find('input[name="qty[]"]').val()) || 0;
                    const harga = parseFloat($(this).find('input[name="harga_satuan[]"]').val()) || 0;
                    subtotal += qty * harga;
                });

                const ongkir = parseFloat($('#ongkir').val()) || 0;
                let dpPersen = parseFloat($('#dp').val()) || 0;

                subtotal += ongkir

                // Validasi DP untuk mencegah angka lebih dari 100
                if (dpPersen > 100) {
                    dpPersen = 100;
                    $('#dp').val(dpPersen);
                }

                const dp = (dpPersen / 100) * subtotal;

                // Hitung total yang belum dibayar
                const totalBelumDibayar = subtotal - dp;

                // Perbarui tampilan
                $('#subtotal').text(formatRupiah(subtotal));
                $('#biaya-kirim').text(formatRupiah(ongkir));
                $('#dp-total').text(formatRupiah(dp));
                $('#total-sisa').text(formatRupiah(totalBelumDibayar));
                $('#status-dp').text(dpPersen === 100 ? 'LUNAS' : 'BELUM LUNAS');
            }


            // Event listener untuk input harga, qty, ongkir
            $(document).on('input', 'input[name="qty[]"], input[name="harga_satuan[]"], #ongkir', function() {
                validateNumberInput($(this));
                calculateTotals();
            });

            // Event listener khusus untuk DP
            $('#dp').on('input', function() {
                let dp = validateNumberInput($(this));
                if (dp > 100) {
                    dp = 100;
                    $(this).val(dp);
                }

                calculateTotals();
            });

            // Hitung total awal saat halaman dimuat
            calculateTotals();
        });
    </script>
@endsection
