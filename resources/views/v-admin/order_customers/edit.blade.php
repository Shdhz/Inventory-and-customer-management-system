@extends('layouts.admin')
@section('content')
    <x-message.errors />
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-header card-header row-cols-auto">
                <div class="col">
                    <span>
                        {{-- Component backurl --}}
                        <x-button.backUrl href="{{ $backUrl }}" />
                    </span>
                </div>
                <div class="col px-2">
                    <h2 class="page-title">{{ $title }}</h2>
                </div>
            </div>
            <form action="{{ route('order-customer.update', $orderCustomer->customer_order_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <!-- Kolom Kiri -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Draft Customer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $orderCustomer->draftCustomer->Nama ?? '-- Pilih Draft Customer --' }}" readonly>
                                <input type="hidden" name="draft_customer_id" value="{{ $orderCustomer->draft_customer_id }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sumber</label>
                                <input type="text" name="sumber" id="sumber_display" class="form-control"
                                    value="{{ $orderCustomer->draftCustomer->sumber ?? '' }}"
                                    placeholder="Sumber akan tampil otomatis" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tipe Order <span class="text-danger">*</span></label>
                                <input type="text" name="tipe_order" id="tipe_order" class="form-control"
                                    value="{{ $orderCustomer->tipe_order }}"
                                    placeholder="Tipe Order (cash/cashless) akan tampil otomatis" disabled>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Jenis Order <span class="text-danger">*</span></label>
                                <select class="form-control" name="jenis_order" required>
                                    <option value="">-- Pilih Jenis Order --</option>
                                    <option value="pre order"
                                        {{ $orderCustomer->jenis_order == 'pre order' ? 'selected' : '' }}>Pre Order
                                    </option>
                                    <option value="ready stock"
                                        {{ $orderCustomer->jenis_order == 'ready stock' ? 'selected' : '' }}>Ready Stock
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="4" placeholder="Tambahkan keterangan jika diperlukan">{{ $orderCustomer->keterangan }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Apply Select2 to the select element
            $('#draft_customer_select').select2({
                placeholder: "-- Pilih Draft Customer --",
                allowClear: true
            });
        });
        document.getElementById('draft_customer_select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const sumber = selectedOption.getAttribute('data-sumber') || '';
            document.getElementById('sumber_display').value = sumber;

            const cashlessSources = ['shopee', 'tokopedia', 'lazada', 'tiktok shop', 'tiktok'];
            const cashSources = ['whatsapp', 'instagram', 'facebook', 'youtube'];

            const sumberLower = sumber.toLowerCase();
            if (cashlessSources.includes(sumberLower)) {
                document.getElementById('tipe_order').value = 'cashless';
            } else if (cashSources.includes(sumberLower)) {
                document.getElementById('tipe_order').value = 'cash';
            } else {
                document.getElementById('tipe_order').value = ''; // Kosongkan jika sumber tidak dikenali
            }
        });
    </script>
@endsection
