<!DOCTYPE html>
<html>
<head>
    <title>Data Kesehatan Kambing</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Data Kesehatan Kambing</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Kambing</th>
                <th>Nama Kambing</th>
                <th>Tanggal Pemeriksaan</th>
                <th>Kondisi</th>
                <th>Perawatan</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $key => $record)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $record->kambing ? $record->kambing->kode_kambing : '-' }}</td>
                <td>{{ $record->kambing ? $record->kambing->nama_kambing : '-' }}</td>
                <td>{{ $record->checkup_date }}</td>
                <td>{{ $record->condition }}</td>
                <td>{{ $record->treatment ?? '-' }}</td>
                <td>{{ $record->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 