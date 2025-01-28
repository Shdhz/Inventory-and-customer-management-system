@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header row-cols-auto">
            <div class="col">
                <x-button.backUrl href="{{ $backUrl }}" />
            </div>
            <div class="col px-2">
                <h2 class="page-title">Detail Model Foto Form PO</h2>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @if (count($models) > 0)
                    @foreach ($models as $model)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="{{ asset('storage/uploads/stok-barang/' . $model->model) }}" class="card-img-top"
                                    alt="Foto Produk">
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            Tidak ada model foto yang tersedia.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
