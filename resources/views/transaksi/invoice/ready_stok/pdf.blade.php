<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 8px;
        }

        table th {
            background-color: #f2f2f2;
        }

        .text-end {
            text-align: right;
        }

        .address {
            font-size: 11px;
        }

        .total-details {
            width: 50%;
            text-align: right;
        }

        .bank-logo {
            width: 10px;
            height: auto;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                margin: 0;
                border: none;
                border-radius: 0;
            }

            .total-section {
                margin-top: 20px;
            }
        }

        @page {
            size: B5 portrait;
            margin: 20mm;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td>
                <!-- Informasi Kaifacraft -->
                <img src="dist/logo_kaifacraftgroup.png" alt="logo_kaifacraft" width="150px">
                <p>Sentra kerajinan tangan unggulan</p>
                <address class="address">
                    Jl. Cikuya RT.03/07 Desa/Kec. Rajapolah<br>
                    Kab. Tasikmalaya - Jawa Barat<br>
                    <span><img src="dist/wa.png" alt="" width="10px"></span> WhatsApp: {{ $user->no_hp }}<br>
                    <span><img src="dist/instagram.png" alt="" width="10px"></span> Instagram:
                    @foreach ($user->instagramForAdmin as $instagram)
                        {{ $instagram->nama_instagram }}
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                    <br>
                </address>
            </td>
            <td class="text-end">
                <p><strong>Tenggat Waktu:</strong>
                    {{ \Carbon\Carbon::parse($invoice->tenggat_invoice)->format('d F Y') }}</p>
                <p><strong>Nota No:</strong> {{ $invoice->nota_no }}</p>
                <p><strong>Kepada:</strong>
                    {{ $invoice->invoiceDetails->first()->transaksiDetail->transaksi->customerOrder->draftCustomer->Nama ?? '-' }}
                </p>
            </td>
        </tr>
    </table>

    <!-- Tabel Detail Produk -->
    <table style="border: 1px solid #ddd">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Harga Satuan</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoiceDetails as $detail)
                <tr>
                    <td>{{ $detail->transaksiDetail->stok->nama_produk ?? 'Tidak ada data' }}</td>
                    <td class="text-center">{{ $detail->transaksiDetail->qty ?? 0 }}</td>
                    <td class="text-end">
                        {{ number_format($detail->transaksiDetail->harga_satuan ?? 0, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($detail->transaksiDetail->subtotal ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Section Total -->
    <table>
        <tr>
            <td>
                <p>Pembayaran via transfer:</p>
                <p>BCA : 6320 3530 82</p>
                <p>BRI : 3466-01-035685-53-3</p>
                <p>a.n. <strong>Sandi Susandi</strong></p>
            </td>
            <td class="total-details">
                <p><strong>Biaya Kirim:</strong> {{ number_format($invoice->ongkir ?? 0, 0, ',', '.') }}</p>
                <p><strong>Grand Total:</strong> {{ number_format($invoice->subtotal ?? 0, 0, ',', '.') }}</p>
                <p><strong>Down Payment (DP):</strong> {{ number_format($invoice->down_payment ?? 0, 0, ',', '.') }}
                </p>
                <p><strong>Total Sisa:</strong> {{ number_format($invoice->total ?? 0, 0, ',', '.') }}</p>
            </td>
        </tr>
    </table>

    <p class="text-end">Hormat Kami,</p>
    <p class="text-end"><strong>Kaifacraft</strong></p>
    <hr>
    <div class="">
        <h3>Members :</h3>
        <img src="dist/members.jpg" alt="members" width="100%" height="auto">
    </div>
</body>

</html>
