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
                    <form action="{{ route('form-po-invoice.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-8">
                                <img src="\dist\logo_kaifacraftgroup.png" alt="logo_kaifacraft.jpg" class="img-fluid img"
                                    width="35%">
                                <p>Sentra kerajinan tangan unggulan</p>
                                <address>
                                    Jl. Cikuya RT.03/07 Desa/kec. Rajapolah<br>
                                    Kab.Tasikmalaya - Jawa Barat<br>
                                    <span>089639152588, 081779200583</span><br>
                                    <span>@kaifa_craft, @kaifacraft, @kerajinanbamburajapolah</span>
                                </address>
                            </div>
                            {{-- <input type="hidden" name="form_po_id" id="form_po_id"> --}}
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="nota_no" class="form-label">Nota No</label>
                                    <input type="text" id="nota_no" name="nota_no" class="form-control"
                                        placeholder="Nomor nota akan di generate otomatis" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tenggat waktu <span
                                            class="text-danger">*</span></label>
                                    <input type="date" id="tanggal" name="tenggat_invoice" class="form-control"
                                        required>
                                    @error('tanggal')
                                        <div class="aform-text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="nama_pelanggan" class="form-label">Kepada <span
                                            class="text-danger">*</span></label>
                                    <select id="nama_pelanggan" name="nama_pelanggan[]" class="form-select"
                                        style="width: 100%;" required>
                                        <option value="">-- Pilih Nama Pelanggan --</option>
                                        @foreach ($formPo as $group)
                                            <option value="{{ $group['customer_order_id'] }}"
                                                data-barang="{{ json_encode($group['data']->toArray() ?? []) }}">
                                                {{ $group['customer_name'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('nama_pelanggan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Invoice pre order hanya untuk pre order yang sudah aktif.</div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="barang-container">
                            <div class="row mb-3 barang-item">
                                <div class="col">
                                    <label for="Nama_Barang_1" class="form-label">Nama Barang <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="Nama_Barang[]" id="Nama_Barang_1" class="form-control"
                                        placeholder="Masukkan Nama Barang" required readonly>
                                    <input type="hidden" name="form_po_id[]" id="form_po_id_1">
                                    @error('Nama_Barang.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="qty_1" class="form-label">Jumlah (Qty) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="qty[]" id="qty_1" class="form-control"
                                        placeholder="Jumlah" min="1" required readonly>
                                    @error('qty.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col input-group mb-3">
                                    <label for="harga_1" class="form-label">Harga Satuan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">Rp</span>
                                        <input type="number" name="harga[]" id="harga_1"
                                            class="form-control harga-satuan" placeholder="Masukkan harga per item"
                                            required>
                                    </div>
                                    @error('harga.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <hr>
                            <div class="mt-4 input-group">
                                <label for="ongkir" class="form-label">Ongkir <span class="text-danger">*</span></label>
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
                                <label for="dp" class="form-label">DP (Down Payment)<span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="number" id="dp" name="dp" class="form-control"
                                        placeholder="Masukkan DP dalam persen" required min="0">
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
                    return 'Rp ' + angka.toLocaleString('id-ID');
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

                // Fungsi untuk menghitung total
                function calculateTotals() {
                    let subtotal = 0;

                    // Hitung subtotal berdasarkan qty dan harga
                    $('.barang-item').each(function() {
                        const qty = validateNumberInput($(this).find('input[name="qty[]"]'));
                        const harga = validateNumberInput($(this).find('input[name="harga[]"]'));
                        subtotal += qty * harga;
                    });

                    // Ambil nilai ongkir dan dp
                    const ongkir = validateNumberInput($('#ongkir'));
                    const dp = validateNumberInput($('#dp'));

                    subtotal += ongkir;

                    // Hitung total yang belum dibayar
                    const totalBelumDibayar = subtotal - dp;
                    const statusDp = dp === subtotal ? 'LUNAS' : 'BELUM LUNAS';

                    // Perbarui tampilan
                    $('#subtotal').text(formatRupiah(subtotal));
                    $('#biaya-kirim').text(formatRupiah(ongkir));
                    $('#dp-total').text(formatRupiah(dp));
                    $('#total-sisa').text(formatRupiah(totalBelumDibayar));
                    $('#status-dp').text(statusDp);
                }

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

                // Hitung total awal saat halaman dimuat
                calculateTotals();
            });
        </script>
    @endsection
