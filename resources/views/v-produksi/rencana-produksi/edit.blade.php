@extends('layouts.produksi')

@section('content')
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-header row-cols-auto">
                <div class="col">
                    <x-button.backUrl href="{{ $backUrl }}" />
                </div>
                <div class="col px-2">
                    <h2 class="page-title">Edit Rencana Produksi</h2>
                </div>
            </div>
            <form action="{{ route('rencana-produksi.update', $rencanaProduksi->id_rencana_produksi) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <!-- Kolom Kiri -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Pilih Form PO</label>
                                <select class="form-control @error('form_po_id') is-invalid @enderror" name="form_po_id"
                                    required>
                                    <option value="" selected>-- Pilih Form PO --</option>
                                    @foreach ($formPo as $po)
                                        <option value="{{ $po->id_form_po }}"
                                            {{ $rencanaProduksi->form_po_id == $po->id_form_po ? 'selected' : '' }}>
                                            {{ $po->keterangan ?? 'No description' }} (admin :
                                            {{ $po->customerOrder->draftCustomer->user->username }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('form_po_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Pengrajin</label>
                                <input type="text" class="form-control @error('nama_pengrajin') is-invalid @enderror"
                                    name="nama_pengrajin"
                                    value="{{ old('nama_pengrajin', $rencanaProduksi->nama_pengrajin) }}" required>
                                @error('nama_pengrajin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai Produksi</label>
                                <input type="datetime-local"
                                    class="form-control @error('mulai_produksi') is-invalid @enderror" name="mulai_produksi"
                                    value="{{ old('mulai_produksi', $rencanaProduksi->mulai_produksi) }}" required>
                                @error('mulai_produksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Berakhir Produksi</label>
                                <input type="datetime-local"
                                    class="form-control @error('berakhir_produksi') is-invalid @enderror"
                                    name="berakhir_produksi"
                                    value="{{ old('berakhir_produksi', $rencanaProduksi->berakhir_produksi) }}" required>
                                @error('berakhir_produksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Prioritas</label>
                                <select class="form-control @error('prioritas') is-invalid @enderror" name="prioritas"
                                    required>
                                    <option value="" selected>-- Pilih Prioritas --</option>
                                    <option value="high"
                                        {{ old('prioritas', $rencanaProduksi->prioritas) == 1 ? 'selected' : '' }}>High
                                    </option>
                                    <option value="medium"
                                        {{ old('prioritas', $rencanaProduksi->prioritas) == 2 ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="low"
                                        {{ old('prioritas', $rencanaProduksi->prioritas) == 3 ? 'selected' : '' }}>Low
                                    </option>
                                </select>
                                @error('prioritas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Status Produksi</label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="" selected>-- Pilih Status --</option>
                                    <option value="produksi"
                                        {{ old('status', $rencanaProduksi->status) == 'produksi' ? 'selected' : '' }}>
                                        Produksi</option>
                                    <option value="selesai"
                                        {{ old('status', $rencanaProduksi->status) == 'selesai' ? 'selected' : '' }}>
                                        Selesai</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Validasi tanggal mulai tidak boleh kurang dari tanggal hari ini
        const startDateInput = document.querySelector('input[name="mulai_produksi"]');
        const endDateInput = document.querySelector('input[name="berakhir_produksi"]');

        startDateInput.addEventListener('change', function() {
            const startDate = new Date(startDateInput.value);
            const today = new Date();

            if (startDate < today) {
                alert('Tanggal mulai tidak boleh kurang dari tanggal hari ini.');
                startDateInput.value = ''; // Reset the date input
            }
        });

        // Validasi tanggal selesai tidak boleh kurang dari tanggal mulai
        endDateInput.addEventListener('change', function() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (endDate < startDate) {
                alert('Tanggal selesai tidak boleh kurang dari tanggal mulai.');
                endDateInput.value = ''; // Reset the date input
            }
        });
    </script>
@endsection
