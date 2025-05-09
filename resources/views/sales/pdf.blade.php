<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: right;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Penjualan Kambing</h2>
        <p>Tanggal: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Kode Kambing</th>
                <th>Nama Kambing</th>
                <th>Jenis</th>
                <th>Harga Jual</th>
                <th>Pembeli</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $index => $sale)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $sale->kode_transaksi }}</td>
                <td>{{ $sale->tgl_penjualan->format('d/m/Y') }}</td>
                <td>{{ $sale->kambing->kode_kambing }}</td>
                <td>{{ $sale->kambing->nama_kambing }}</td>
                <td>{{ $sale->kambing->jenis->nama_jenis }}</td>
                <td>Rp {{ number_format($sale->harga_jual, 0, ',', '.') }}</td>
                <td>{{ $sale->pembeli }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" style="text-align: right">Total:</th>
                <th colspan="2">Rp {{ number_format($sales->sum('harga_jual'), 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 