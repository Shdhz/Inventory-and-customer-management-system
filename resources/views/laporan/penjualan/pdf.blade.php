<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        h1, h2 {
            text-align: center;
        }
        .header {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h1>Laporan Penjualan</h1>
    <h2>Periode: {{ $start_date }} - {{ $end_date }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Tanggal Keluar</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Diskon Produk</th>
                <th>Diskon Ongkir</th>
                <th>Ekspedisi</th>
                <th>Pembayaran</th>
                <th>Customer</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->stok->nama_produk ?? '-' }}</td>
                    <td>{{ $item->tanggal_keluar }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    <td>{{ $item->transaksi->diskon_produk ?? '-' }}</td>
                    <td>{{ $item->transaksi->diskon_ongkir ?? '-' }}</td>
                    <td>{{ $item->transaksi->ekspedisi ?? '-' }}</td>
                    <td>{{ $item->transaksi->metode_pembayaran ?? '-' }}</td>
                    <td>{{ $item->transaksi->customerOrder->draftCustomer->Nama ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center;">Data tidak ditemukan untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
    </div>
</body>
</html>
