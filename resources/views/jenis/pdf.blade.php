<!DOCTYPE html>
<html>
<head>
    <title>Data Jenis Kambing</title>
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
    <h1 style="text-align: center;">Data Jenis Kambing</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Kambing</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jenis as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->jenis_kambing }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 