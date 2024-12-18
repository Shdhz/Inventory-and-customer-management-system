@extends('layouts.admin')
@section('content')
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
        <form method="POST" action="">
            @csrf
            <div class="card mt-3 p-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <img src="\dist\logo.gif" alt="logo_kaifacraft.jpg" class="img-fluid img" width="30%">
                            <p>Sentra kerajinan tangan unggulan</p>
                            <address>
                                Jl. Cikuya RT.03/07 Desa/kec. Rajapolah<br>
                                Kab.Tasikmalaya - Jawa Barat<br>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-brand-whatsapp">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
                                        <path
                                            d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
                                    </svg>
                                </span> 089639152588, 081779200583<br>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-brand-instagram">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M4 8a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                                        <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                        <path d="M16.5 7.5v.01" />
                                    </svg> @kaifa_craft , @kaifacraft , @kerajinanbamburajapolah
                                </span>
                            </address>
                        </div>

                        <div class="col-4">
                            <div class="mb-3">
                                <label for="nota_no" class="form-label">Nota No</label>
                                <input type="text" id="nota_no" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" id="tanggal" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="nama_pelanggan" class="form-label">Kepada</label>
                                <select id="nama_pelanggan" class="form-select">
                                    <option value="BAF 02">Nama pelanggan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row my-4">
                        <div class="col-12">
                            <h1 class="text-start">Invoice no </h1>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="kode_barang" class="form-label">Nama Barang</label>
                            <input type="text" name="kode_barang" id="kode_barang" class="form-control">
                        </div>
                        <div class="col">
                            <label for="qty" class="form-label">Qty</label>
                            <input type="number" id="qty" class="form-control" min="1" placeholder="Jumlah">
                        </div>
                        <div class="col">
                            <label for="harga" class="form-label">Harga Satuan</label>
                            <input type="text" id="harga" class="form-control">
                        </div>
                        <div class="col">
                            <label for="ongkir" class="form-label">Biaya kirim</label>
                            <input type="text" id="ongkir" name="ongkir" class="form-control">
                        </div>
                        <div class="col">
                            <label for="down_payment" class="form-label">Down payment(Dp)</label>
                            <input type="text" id="down_payment" name="down_payment" class="form-control">
                        </div>
                    </div>

                    <table class="table table-bordered" id="barang-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Jumlah</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be added dynamically -->
                        </tbody>
                    </table>

                    <div class="row mt-4">
                        <div class="col-10">
                            <p class="mb-2">Pembayaran via transfer :</p>
                            <div class="d-flex align-items-center">
                                <span class="me-2">
                                    <img src="/BCA.svg" alt="BCA Logo">
                                </span>
                                : 6320 3530 82 <span class="ms-3">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">
                                            <img src="/BRI.svg" alt="BRI Logo">
                                        </span>
                                        : 3466-01-035685-53-3
                                    </div>
                                </span>
                            </div>
                            <p class="mt-2">a.n. <strong>Sandi Susandi</strong></p>
                        </div>
                        <div class="col-2">
                            <p>Sub Total : <span id="subtotal">0</span></p>
                            <p>Biaya kirim : <span id="biaya-kirim">0</span></p>
                            <p>Down payment(Dp) : <span id="dp">0</span></p>
                            <p>Total/sisa belum : <span id="total-sisa">0</span></p>
                            <br>
                            <p class="text-center">Hormat Kami,</p>
                            <p class="text-center"><br><strong>Kaifacraft</strong></p>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-4">
                        <h3>Members :</h3>
                        <img src="\dist\members.svg" alt="" width="100%">
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnAddBarang = document.getElementById("add-barang");
            const tableBarang = document.querySelector("#barang-table tbody");

            btnAddBarang.addEventListener("click", function() {
                // Ambil nilai input
                const kodeBarang = document.getElementById("kode_barang").value;
                const qty = parseInt(document.getElementById("qty").value) || 0;
                const harga = parseFloat(document.getElementById("harga").value) || 0;
                const ongkir = parseFloat(document.getElementById("ongkir").value) || 0;
                const downPayment = parseFloat(document.getElementById("down_payment").value) || 0;

                const jumlahPerItem = qty * harga;
                const totalPerItem = jumlahPerItem + ongkir + downPayment;

                if (!kodeBarang || qty <= 0 || harga <= 0) {
                    alert("Pastikan semua input diisi dengan benar!");
                    return;
                }

                // Tambahkan baris ke tabel
                const row = document.createElement("tr");
                row.innerHTML = `
            <td>${kodeBarang}</td>
            <td>${kodeBarang} - Nama Barang</td>
            <td class="text-center">${qty}</td>
            <td class="text-end">${harga.toLocaleString()}</td>
            <td class="text-end">${totalPerItem.toLocaleString()}</td>
            <td class="text-center">
                <button class="btn btn-danger btn-sm remove-barang">Hapus</button>
            </td>
        `;
                tableBarang.appendChild(row);

                // Reset input
                document.getElementById("kode_barang").value = "";
                document.getElementById("qty").value = "";
                document.getElementById("harga").value = "";

                // Update subtotal
                updateSubtotal();
            });

            // Event untuk menghapus baris barang
            tableBarang.addEventListener("click", function(e) {
                if (e.target.classList.contains("remove-barang")) {
                    e.target.closest("tr").remove();
                    updateSubtotal();
                }
            });

            function updateSubtotal() {
                let subtotal = 0;
                let biayaKirim = 0;
                let downPayment = 0;
                document.querySelectorAll("#barang-table tbody tr").forEach(row => {
                    const jumlah = parseFloat(row.cells[4].innerText.replace(/,/g, "")) || 0;
                    subtotal += jumlah;
                });

                // Update subtotal dan total
                document.querySelector(".col-2 p:nth-child(1)").innerText =
                    `Sub Total: ${subtotal.toLocaleString()}`;
                document.querySelector(".col-2 p:nth-child(2)").innerText =
                    `Biaya kirim: ${biayaKirim.toLocaleString()}`;
                document.querySelector(".col-2 p:nth-child(3)").innerText =
                    `Down payment(Dp): ${downPayment.toLocaleString()}`;
                document.querySelector(".col-2 p:nth-child(4)").innerText =
                    `Total/sisa belum: ${(subtotal + biayaKirim - downPayment).toLocaleString()}`;
            }
        });
    </script> --}}
@endsection
