<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
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
    </style>
</head>
<body>
    <h1>Riwayat transaksi</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
                <th>Tanggal Transaksi</th>
                <th>Customer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->stok->nama_produk ?? '-' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->harga_satuan, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d F Y') }}</td>
                    <td>{{ $item->transaksi->customerOrder->draftCustomer->Nama ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
