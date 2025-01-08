<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Laporan Penjualan</h1>
    <h2>Periode: {{ $request->start_date ?? 'Semua' }} - {{ $request->end_date ?? 'Semua' }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Tanggal Keluar</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
                <th>Diskon Produk</th>
                <th>Diskon Ongkir</th>
                <th>Ekspedisi</th>
                <th>Pembayaran</th>
                <th>Customer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->stok->nama_produk ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d F Y') }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->harga_satuan, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                    <td>{{ $item->diskon_produk ?? '-' }}%</td>
                    <td>{{ $item->diskon_ongkir ?? '-' }}%</td>
                    <td>{{ $item->transaksi->ekspedisi ?? '-' }}</td>
                    <td>{{ $item->transaksi->metode_pembayaran ?? '-' }}</td>
                    <td>{{ $item->transaksi->customerOrder->draftCustomer->Nama ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
    </div>
</body>
</html>
