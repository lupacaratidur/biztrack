<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Penjualan</title>
    <style>
        /* Tambahkan gaya CSS sesuai kebutuhan untuk desain laporan */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Laporan Penjualan</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pembelian</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Tanggal Transaksi</th>
                <th>Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelians as $key => $pembelian)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $pembelian->kode_pembelian }}</td>
                    <td>Rp. {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $pembelian->status === 'paid' ? 'Paid' : 'Unpaid' }}</td>
                    <td>{{ $pembelian->tgl_transaksi }}</td>
                    <td>
                        @foreach ($pembelian->detailPembelians as $detail)
                            {{ $detail->nama }} (Qty: {{ $detail->quantity }})<br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
