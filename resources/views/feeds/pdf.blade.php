<!DOCTYPE html>
<html>
<head>
    <title>Data Pakan dan Obat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Data Pakan dan Obat</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pakan/Obat</th>
                <th>Tanggal Beli</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Jumlah Beli</th>
            </tr>
        </thead>
        <tbody>
            @foreach($feeds as $index => $feed)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $feed->nama }}</td>
                <td>{{ date('d/m/Y', strtotime($feed->tgl_beli)) }}</td>
                <td>{{ $feed->satuan }}</td>
                <td>Rp {{ number_format($feed->harga, 0, ',', '.') }}</td>
                <td>{{ $feed->jumlah_beli }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 