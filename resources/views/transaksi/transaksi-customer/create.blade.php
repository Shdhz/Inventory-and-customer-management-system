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
                <form action="{{ route('transaksi-customer.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <label for="customer" class="form-label">Pilih Customer <span class="text-danger">*</span></label>
                        <select class="form-control @error('customer_order_id') is-invalid @enderror"
                            name="customer_order_id" id="customer" required>
                            <option value="" selected>-- Pilih Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->customer_order_id }}"
                                    {{ old('customer_order_id') == $customer->customer_order_id ? 'selected' : '' }}>
                                    {{ $customer->draftCustomer->Nama ?? $customer->Nama }} -
                                    ({{ $customer->sumber ?? 'Tidak Diketahui' }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_order_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <label for="products" class="form-label">Pilih Produk <span class="text-danger">*</span></label>
                        <div id="product-container">
                            <!-- Form Dinamis untuk Input Produk -->
                            <div class="product-row d-flex gap-2 mb-2">
                                <select class="form-control @error('products.*.stok_id') is-invalid @enderror"
                                    name="products[0][stok_id]" required>
                                    <option value="" selected>-- Pilih Produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id_stok }}">
                                            {{ old('products[0][stok_id]') == $product->id_stok ? 'selected' : '' }}
                                            {{ $product->nama_produk }} (Stok : {{ $product->jumlah_stok }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('products.0.stok_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <input type="number"
                                    class="form-control qty-input @error('products.*.qty') is-invalid @enderror"
                                    name="products[0][qty]" placeholder="Qty" required>
                                @error('products.0.qty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <input type="number"
                                    class="form-control price-input @error('products.*.harga_satuan') is-invalid @enderror"
                                    name="products[0][harga_satuan]" placeholder="Harga" required>
                                @error('products.0.harga_satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <button type="button" class="btn btn-danger remove-product">Hapus</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary mt-2" id="add-product">Tambah Produk</button>
                    </div>

                    <div class="row mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran <span
                                class="text-danger">*</span></label>
                        <select class="form-control @error('payment_method') is-invalid @enderror" name="payment_method"
                            id="payment_method" required>
                            <option value="" selected>-- Pilih Metode Pembayaran --</option>
                            <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>COD</option>
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer
                            </option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <label for="expedition" class="form-label">Ekspedisi</label>
                        <input type="text" class="form-control @error('expedition') is-invalid @enderror"
                            name="expedition" id="expedition" placeholder="Nama Ekspedisi" value="{{ old('expedition') }}">
                        @error('expedition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <label for="discount_product" class="form-label">Diskon Produk (%)</label>
                        <input type="number" class="form-control @error('discount_product_percent') is-invalid @enderror"
                            name="discount_product_percent" id="discount_product_percent"
                            value="{{ old('discount_product_percent', 0) }}" min="0" max="100">
                        @error('discount_product_percent')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <h4>Subtotal: <span id="subtotal">0</span></h4>
                        <h4>Total: <span id="total">0</span></h4>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let productIndex = 1;
            const products = @json($products);
            let selectedProducts = [];

            function addProductRow(index) {
                return `
        <div class="product-row d-flex gap-2 mb-2">
            <select class="form-control product-select" name="products[${index}][stok_id]" required>
                <option value="" selected>-- Pilih Produk --</option>
                ${products.map(product => `<option value="${product.id_stok}" data-stok="${product.jumlah_stok}">${product.nama_produk} (Stok : ${product.jumlah_stok})</option>`).join('')}
            </select>
            <input type="number" class="form-control qty-input" name="products[${index}][qty]" placeholder="Qty" required min="0">
            <input type="number" class="form-control price-input" name="products[${index}][harga_satuan]" placeholder="Harga" required min="0">
            <button type="button" class="btn btn-danger remove-product">Hapus</button>
        </div>`;
            }

            $('#add-product').click(function() {
                $('#product-container').append(addProductRow(productIndex));
                productIndex++;
                updateProductSelection();
                bindProductEvents();
                calculateTotal();
            });

            function bindProductEvents() {
                $('.remove-product').off('click').on('click', function() {
                    $(this).closest('.product-row').remove();
                    updateProductSelection();
                    calculateTotal();
                });

                $('.product-select').off('change').on('change', function() {
                    updateProductSelection();
                    calculateTotal();
                });

                $('.qty-input').off('input').on('input', function() {
                    let value = $(this).val().replace(/\D/g, '');
                    value = value === '' ? 1 : Math.max(1, parseInt(value));
                    $(this).val(formatCurrency(value.toString()));
                    calculateTotal();
                });

                $('.price-input').off('input').on('input', function() {
                    let value = $(this).val().replace(/\D/g, '');
                    $(this).val(formatCurrency(value));
                    calculateTotal();
                });
            }

            // Update product selection to prevent duplicate selections
            function updateProductSelection() {
                const allSelectedProducts = $('.product-select').map(function() {
                    return $(this).val();
                }).get();

                const productCounts = {};
                allSelectedProducts.forEach(productId => {
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

            // Update product selection to prevent duplicate selections
            function updateProductSelection() {
                const allSelectedProducts = $('.product-select').map(function() {
                    return $(this).val();
                }).get();

                const productCounts = {};
                allSelectedProducts.forEach(productId => {
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

            // Calculate total, subtotal, and apply discount
            function calculateTotal() {
                let subtotal = 0;
                $('.product-row').each(function() {
                    const qty = parseInt($(this).find('.qty-input').val().replace(/\D/g, '') || 0, 10);
                    const price = parseInt($(this).find('.price-input').val().replace(/\D/g, '') || 0, 10);
                    subtotal += qty * price;
                });

                const discountProductPercent = parseInt($('#discount_product_percent').val() || 0, 10);
                let discountProduct = Math.floor((subtotal * discountProductPercent) / 100);

                discountProduct = Math.min(discountProduct, subtotal);

                const total = subtotal - discountProduct;

                // Update displayed values
                $('#subtotal').text(formatCurrency(subtotal.toString()));
                $('#total').text(formatCurrency(total.toString()));
            }

            // Format numbers with thousands separators
            function formatCurrency(value) {
                return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Handle discount percentage input
            $('#discount_product_percent').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                value = Math.min(Math.max(value, 0), 100);
                $(this).val(value);
                calculateTotal();
            });

            // Form submission preparation
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
