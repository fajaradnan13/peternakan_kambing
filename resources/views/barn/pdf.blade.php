<!DOCTYPE html>
<html>
<head>
    <title>Data Kandang</title>
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
    </style>
</head>
<body>
    <h1 style="text-align: center;">Data Kandang</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kandang</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barns as $key => $barn)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $barn->name }}</td>
                <td>{{ $barn->location }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 