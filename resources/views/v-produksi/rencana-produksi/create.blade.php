@extends('layouts.produksi')

@section('content')
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-header row-cols-auto">
                <div class="col">
                    <x-button.backUrl href="{{ $backUrl }}" />
                </div>
                <div class="col px-2">
                    <h2 class="page-title">Tambah Rencana Produksi</h2>
                </div>
            </div>
            <form action="{{ route('rencana-produksi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <!-- Kolom Kiri -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Pilih Form PO</label>
                                <select class="form-control" name="form_po_id" id="formPoSelect" required>
                                    <option value="" selected>-- Pilih Form PO --</option>
                                    @foreach ($formPo as $po)
                                        <option value="{{ $po->id_form_po }}"
                                            data-models="{{ json_encode(
                                                $po->modelsFormpo->map(function ($model) {
                                                    return asset('storage/uploads/stok-barang/' . $model->model);
                                                }),
                                            ) }}">
                                            {{ $po->keterangan ?? 'No description' }} (admin :
                                            {{ $po->customerOrder->draftCustomer->user->username }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('form_po_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview Model PO -->
                            <div class="mb-3">
                                <label class="form-label">Preview Model</label>
                                <div id="modelPreview" class="border p-3" style="background: #f8f9fa;">
                                    <div id="previewImages"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Pengrajin</label>
                                <input type="text" class="form-control @error('nama_pengrajin') is-invalid @enderror"
                                    name="nama_pengrajin" value="{{ old('nama_pengrajin') }}" required>
                                @error('nama_pengrajin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai Produksi</label>
                                <input type="datetime-local"
                                    class="form-control @error('mulai_produksi') is-invalid @enderror" name="mulai_produksi"
                                    value="{{ old('mulai_produksi') }}" required>
                                @error('mulai_produksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Berakhir Produksi</label>
                                <input type="datetime-local"
                                    class="form-control @error('berakhir_produksi') is-invalid @enderror"
                                    name="berakhir_produksi" value="{{ old('berakhir_produksi') }}" required>
                                @error('berakhir_produksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Prioritas</label>
                                <select class="form-control @error('prioritas') is-invalid @enderror" name="prioritas"
                                    required>
                                    <option value="" selected>-- Pilih Prioritas --</option>
                                    <option value="high" {{ old('prioritas') == 1 ? 'selected' : '' }}>High</option>
                                    <option value="medium" {{ old('prioritas') == 2 ? 'selected' : '' }}>Medium</option>
                                    <option value="low" {{ old('prioritas') == 3 ? 'selected' : '' }}>Low</option>
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
                                    <option value="produksi" {{ old('status') == 'produksi' ? 'selected' : '' }}>Produksi
                                    </option>
                                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                    </option>
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
                    <button type="submit" class="btn btn-primary">Simpan</button>
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

        document.getElementById("formPoSelect").addEventListener("change", function() {
            let selectedOption = this.options[this.selectedIndex];
            let modelsJson = selectedOption.getAttribute("data-models");
            let models = JSON.parse(modelsJson);

            let previewDiv = document.getElementById("previewImages");
            previewDiv.innerHTML = "";
            if (models.length > 0) {
                models.forEach(function(image) {
                    let img = document.createElement("img");
                    img.src = image; 
                    img.style = "max-width: 100px; margin: 5px;";
                    previewDiv.appendChild(img);
                });
            } else {
                previewDiv.innerHTML = "<p class='text-muted'>Tidak ada model tersedia.</p>";
            }
        });
    </script>
@endsection
