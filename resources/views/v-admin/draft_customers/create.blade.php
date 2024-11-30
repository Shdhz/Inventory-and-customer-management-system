@extends('layouts.admin')
@section('title', 'Add Draft Customers')
@section('content')
<div class="container-lg mt-2">
    <div class="card">
        <div class="card-header">
            <h3>Tambah Draft Customer</h3>
        </div>
        <form action="}" method="POST">
            @csrf
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    <!-- Kolom Kiri -->
                    <div class="flex-grow-1">
                        <div class="mb-3">
                            <label class="form-label">Nama Customer</label>
                            <input type="text" class="form-control" name="nama" placeholder="Username" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="example@gmail.com" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kota</label>
                            <input type="text" class="form-control" name="kota" placeholder="Kota" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sumber</label>
                            <input type="text" class="form-control" name="sumber" placeholder="sumber">
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="flex-grow-1">
                        <div class="mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" class="form-control" name="no_hp" placeholder="No HP" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Provinsi</label>
                            <input type="text" class="form-control" name="provinsi" placeholder="Provinsi" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" class="form-control" rows="4" placeholder="Contoh: Jl. Sukasukur, Kp. Majalengka..." required></textarea>
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
