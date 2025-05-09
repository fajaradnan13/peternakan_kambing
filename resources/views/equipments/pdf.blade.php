<!DOCTYPE html>
<html>
<head>
    <title>Data Alat dan Perlengkapan</title>
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
    <h1>Data Alat dan Perlengkapan</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Tanggal Beli</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Jumlah Beli</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipments as $index => $equipment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $equipment->nama }}</td>
                <td>{{ $equipment->kategori }}</td>
                <td>{{ date('d/m/Y', strtotime($equipment->tgl_beli)) }}</td>
                <td>{{ $equipment->satuan }}</td>
                <td>Rp {{ number_format($equipment->harga, 0, ',', '.') }}</td>
                <td>{{ $equipment->jumlah_beli }}</td>
                <td>{{ $equipment->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 