@extends('layouts.supervisor')
@section('content')
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-header card-header row-cols-auto">
                <div class="col">
                    <x-button.backUrl href="{{ $backUrl }}" />
                </div>
                <div class="col px-2">
                    <h2 class="page-title">{{ $title }}</h2>
                </div>
            </div>
            <form action="{{ route('kelola-admin.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div class="mb-3">
                        <label class="form-label">No hp <span class="text-danger">*</span></label>
                        <input type="number" id="no_hp_input" name="no_hp"
                            class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}"
                            maxlength="13" required>
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Instagram -->
                    <div id="instagram-fields">
                        <div class="row mb-3">
                            <div class="col-md-10">
                                <label class="form-label">Instagram <span class="text-danger">*</span></label>
                                <input type="text" name="instagram[]"
                                    class="form-control @error('instagram.*') is-invalid @enderror"
                                    value="{{ old('instagram.0') }}" required>
                                @error('instagram.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" id="add-instagram" class="btn btn-primary">Tambah Instagram</button>
                            </div>
                        </div>
                    </div>


                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" placeholder="Password minimal 6 karakter." name="password"
                            class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Password harus mengandung:
                            <ul>
                                <li>Minimal 6 karakter</li>
                                <li>Minimal 1 huruf besar (A-Z)</li>
                                <li>Minimal 1 huruf kecil (a-z)</li>
                                <li>Minimal 1 angka (0-9)</li>
                                <li>Minimal 1 karakter khusus (!@#$%^&*)</li>
                            </ul>
                        </small>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
        // Membatasi panjang nomor HP sampai 13 digit
        document.getElementById('no_hp_input').addEventListener('input', function(e) {
            if (this.value.length > 13) {
                this.value = this.value.slice(0, 13);
            }
        });

        document.getElementById('add-instagram').addEventListener('click', function() {
            const newField = `
            <div class="row mb-3">
                <div class="col-md-10">
                    <label class="form-label">Instagram <span class="text-danger">*</span></label>
                    <input type="text" name="instagram[]"
                        class="form-control" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-instagram">Hapus</button>
                </div>
            </div>
        `;
            document.getElementById('instagram-fields').insertAdjacentHTML('beforeend', newField);
        });

        document.getElementById('instagram-fields').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-instagram')) {
                e.target.closest('.row').remove();
            }
        });
    </script>
@endsection
