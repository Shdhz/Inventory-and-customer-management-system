@extends('layouts.admin')
@section('title', 'Add Draft Customers')
@section('content')
    <div class="container-lg mt-2">
        <div class="card">
            <div class="card-header card-header row-cols-auto">
                <div class="col">
                    <span>
                        {{-- component backurl --}}
                        <x-button.backUrl href="{{ $backUrl }}" />
                    </span>
                </div>
                <div class="col px-2">
                    <h2 class="page-title">{{ $title }}</h2>
                </div>
            </div>
            <form action="{{ route('draft-customer.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <!-- Kolom Kiri -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">Nama Customer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('Nama') is-invalid @enderror"
                                    name="Nama" placeholder="Nama customer" value="{{ old('Nama') }}" required />
                                @error('Nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" placeholder="example@gmail.com" value="{{ old('email') }}" />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kota</label>
                                <input type="text" class="form-control @error('kota') is-invalid @enderror"
                                    name="kota" placeholder="Kota" value="{{ old('kota') }}" />
                                @error('kota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 form-group">
                                <label class="form-label">Sumber <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sumber') is-invalid @enderror"
                                    name="sumber" placeholder="Sumber" value="{{ old('sumber') }}" required />
                                @error('sumber')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <strong>Jenis Cash:</strong> Instagram, WhatsApp, Facebook<br>
                                    <strong>Jenis Cashless:</strong> Shopee, Tokopedia, Lazada, TikTok Shop, TikTok
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label class="form-label">No HP <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('no_hp') is-invalid @enderror"
                                    name="no_hp" placeholder="No HP" value="{{ old('no_hp') }}" required maxlength="13"/>
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Provinsi</label>
                                <input type="text" class="form-control @error('provinsi') is-invalid @enderror"
                                    name="provinsi" placeholder="Provinsi" value="{{ old('provinsi') }}" />
                                @error('provinsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" class="form-control @error('alamat_lengkap') is-invalid @enderror" rows="4"
                                    placeholder="Contoh: Jl. Sukasukur, Kp. Majalengka...">{{ old('alamat_lengkap') }}</textarea>
                                @error('alamat_lengkap')
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
@endsection
