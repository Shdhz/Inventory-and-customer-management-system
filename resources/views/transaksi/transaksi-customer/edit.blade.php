@extends('layouts.admin')

@section('content')
    <x-message.errors />
    <div class="container">
        <div class="card mt-3">
            <div class="row card-header row-cols-auto">
                <div class="col">
                    {{-- Component backurl --}}
                    <x-button.backUrl href="{{ $backUrl }}" />
                </div>
                <div class="col px-2">
                    <h2 class="page-title">{{ $title }}</h2>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksi-customer.update', $transaksi->id_transaksi) }}" method="POST">
                    @csrf
                    @method('PUT')
                    {{-- Pilihan Customer --}}
                    <input type="hidden" name="transaction_id" value="{{ $transaksi->id }}">
                    <div class="row mb-3">
                        <label for="customer_order_id" class="form-label">Pilih Customer</label>
                        <select class="form-control @error('customer_order_id') is-invalid @enderror"
                            name="customer_order_id" id="customer" required>
                            <option value="" selected>-- Pilih Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->customer_order_id }}"
                                    {{ old('customer_order_id', $transaksi->customer_order_id) == $customer->customer_order_id ? 'selected' : '' }}>
                                    {{ $customer->draftCustomer->Nama ?? $customer->Nama }}
                                    - ({{ $customer->sumber ?? 'Tidak Diketahui' }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_order_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pilihan Produk --}}
                    <div class="row mb-3">
                        <label for="products" class="form-label">Pilih Produk</label>
                        <div id="product-container">
                            {{-- Menampilkan Produk yang Sudah Ada --}}
                            @foreach ($transaksiDetails as $index => $detail)
                                <div class="product-row d-flex gap-2 mb-2">
                                    <select class="form-control" name="products[{{ $index }}][stok_id]" required>
                                        <option value="" selected>-- Pilih Produk --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id_stok }}"
                                                {{ old('products.' . $index . '.stok_id', $detail->stok_id) == $product->id_stok ? 'selected' : '' }}>
                                                {{ $product->nama_produk }} (Stok : {{ $product->jumlah_stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" class="form-control qty-input"
                                        name="products[{{ $index }}][qty]" placeholder="Qty"
                                        value="{{ $detail->qty }}" required>
                                    <input type="text" class="form-control price-input"
                                        name="products[{{ $index }}][harga_satuan]" placeholder="Harga"
                                        value="{{ number_format($detail->harga_satuan, 0, ',', '.') }}" required>
                                    <button type="button" class="btn btn-danger remove-product">Hapus</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary mt-2" id="add-product">Tambah Produk</button>
                    </div>

                    <div class="row mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran</label>
                        <select class="form-control @error('payment_method') is-invalid @enderror" name="payment_method"
                            id="payment_method" required>
                            <option value=" " selected>-- Pilih Metode Pembayaran --</option>
                            <option value="cod"
                                {{ old('payment_method', $transaksi->metode_pembayaran) == 'cod' ? 'selected' : '' }}>COD
                            </option>
                            <option value="transfer"
                                {{ old('payment_method', $transaksi->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>
                                Transfer
                            </option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Total Harga --}}
                    <div class="row mb-3">
                        <label for="expedition" class="form-label">Ekspedisi</label>
                        <input type="text" class="form-control @error('expedition') is-invalid @enderror"
                            name="expedition" id="expedition" placeholder="Nama Ekspedisi"
                            value="{{ old('expedition', $transaksi->ekspedisi) }}">
                        @error('expedition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <label for="discount_product" class="form-label">Diskon Produk (%)</label>
                        <input type="number" class="form-control @error('discount_product_percent') is-invalid @enderror"
                            name="discount_product_percent" id="discount_product_percent"
                            value="{{ old('discount_product_percent', $transaksi->diskon_produk) }}" min="0"
                            max="100" step="1">
                        @error('discount_product_percent')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Subtotal dan Total --}}
                    <div class="row mb-3">
                        <h4>Subtotal: <span id="subtotal">0</span></h4>
                        <h4>Total: <span id="total">0</span></h4>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk Form Dinamis --}}
    <script>
        $(document).ready(function() {
            let productIndex = {{ $transaksiDetails->count() }}; // Mulai dari jumlah data transaksiDetail yang ada
            const products = @json($products); // Data semua produk

            // Fungsi untuk menambahkan baris produk
            function addProductRow(index, stokId = "", qty = "", harga = "") {
                return `
                    <div class="product-row d-flex gap-2 mb-2">
                        <select class="form-control product-select" name="products[${index}][stok_id]" required>
                            <option value="" selected>-- Pilih Produk --</option>
                            ${products.map(product => `
                                    <option value="${product.id_stok}" data-stok="${product.jumlah_stok}" 
                                        ${product.id_stok == stokId ? "selected" : ""}>
                                        ${product.nama_produk} (Stok: ${product.jumlah_stok})
                                    </option>`).join('')}
                        </select>
                        <input type="text" class="form-control qty-input" name="products[${index}][qty]" 
                            value="${qty}" placeholder="Qty" required>
                        <input type="text" class="form-control price-input" name="products[${index}][harga_satuan]" 
                            value="${harga}" placeholder="Harga" required>
                        <button type="button" class="btn btn-danger remove-product">Hapus</button>
                    </div>`;
            }
            // Tambahkan baris produk baru
            $('#add-product').click(function() {
                $('#product-container').append(addProductRow(productIndex));
                productIndex++;
                bindProductEvents();
                calculateTotal();
            });

            // Bind event untuk elemen dinamis
            function bindProductEvents() {
                // Hapus baris produk
                $('.remove-product').off('click').on('click', function() {
                    $(this).closest('.product-row').remove();
                    calculateTotal();
                });

                // Perbarui total ketika qty berubah
                $('.qty-input').off('input').on('input', function() {
                    let value = $(this).val().replace(/\D/g, '');
                    value = value === '' ? 1 : Math.max(1, parseInt(value));
                    $(this).val(formatCurrency(value.toString()));
                    calculateTotal();
                });

                // Perbarui total ketika harga berubah
                $('.price-input').off('input').on('input', function() {
                    let value = $(this).val().replace(/\D/g, '');
                    $(this).val(formatCurrency(value));
                    calculateTotal();
                });

                // Cegah duplikasi pilihan produk
                $('.product-select').off('change').on('change', function() {
                    updateProductSelection();
                    calculateTotal();
                });
            }

            // Mencegah duplikasi produk
            function updateProductSelection() {
                const selectedProducts = $('.product-select').map(function() {
                    return $(this).val();
                }).get();

                const productCounts = {};
                selectedProducts.forEach(productId => {
                    if (productId) {
                        productCounts[productId] = (productCounts[productId] || 0) + 1;
                    }
                });

                $('.product-select').each(function() {
                    const currentSelect = $(this);
                    const currentSelectedValue = currentSelect.val();

                    currentSelect.find('option').each(function() {
                        const option = $(this);
                        const optionValue = option.val();

                        if (optionValue) {
                            if (productCounts[optionValue] > 0 && optionValue !==
                                currentSelectedValue) {
                                option.prop('disabled', true);
                            } else {
                                option.prop('disabled', false);
                            }
                        }
                    });
                });
            }

            // Hitung subtotal dan total
            function calculateTotal() {
                let subtotal = 0;
                $('.product-row').each(function() {
                    const qty = parseInt($(this).find('.qty-input').val().replace(/\D/g, '') || 0, 10);
                    const price = parseInt($(this).find('.price-input').val().replace(/\D/g, '') || 0, 10);
                    subtotal += qty * price;
                });

                const discountPercent = parseInt($('#discount_product_percent').val() || 0, 10);
                let discount = Math.floor((subtotal * discountPercent) / 100);
                discount = Math.min(discount, subtotal);

                const total = subtotal - discount;

                // Tampilkan subtotal dan total
                $('#subtotal').text(formatCurrency(subtotal.toString()));
                $('#total').text(formatCurrency(total.toString()));
            }

            // Format angka menjadi format ribuan
            function formatCurrency(value) {
                return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Bind event input diskon
            $('#discount_product_percent').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                value = Math.min(Math.max(value, 0), 100);
                $(this).val(value);
                calculateTotal();
            });

            // Siapkan form untuk submit
            $('form').on('submit', function() {
                $('.price-input, .qty-input').each(function() {
                    const rawValue = $(this).val().replace(/\./g, '');
                    $(this).val(rawValue);
                });
            });

            // Initial setup
            bindProductEvents();
            updateProductSelection();
            calculateTotal();
        });
    </script>
@endsection
