<!DOCTYPE html>
<html>
<head>
    <title>Detail Kambing - {{ $kambing->kode_kambing }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .photo {
            max-width: 200px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detail Kambing</h1>
    </div>
    <div class="section">
        <div class="section-title">Foto</div>
        @if($kambing->foto)
            <img src="{{ public_path($kambing->foto) }}" class="photo">
        @else
            <p>Tidak ada foto</p>
        @endif
    </div>
    
    <div class="section">
        <div class="section-title">Informasi Dasar</div>
        <table>
            <tr>
                <th>Kode Kambing</th>
                <td>{{ $kambing->kode_kambing }}</td>
            </tr>
            <tr>
                <th>Nama Kambing</th>
                <td>{{ $kambing->nama_kambing }}</td>
            </tr>
            <tr>
                <th>Jenis</th>
                <td>{{ $kambing->jenis->jenis_kambing }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ $kambing->jenis_kelamin }}</td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td>{{ $kambing->tanggal_lahir }}</td>
            </tr>
            <tr>
                <th>Warna</th>
                <td>{{ $kambing->warna }}</td>
            </tr>
            <tr>
                <th>Kandang</th>
                <td>{{ $kambing->barn->name }}</td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td>{{ $kambing->keterangan ?? '-' }}</td>
            </tr>
        </table>
    </div>

    

    <div class="section">
        <div class="section-title">Riwayat Kesehatan</div>
        @if($kambing->healthRecords->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Pemeriksaan</th>
                        <th>Kondisi Kesehatan</th>
                        <th>Kehamilan</th>
                        <th>Kondisi</th>
                        <th>Perawatan</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kambing->healthRecords as $record)
                        <tr>
                            <td>{{ $record->checkup_date }}</td>
                            <td>{{ $record->kondisi_kesehatan }}</td>
                            <td>{{ $record->kehamilan ? 'Ya' : 'Tidak' }}</td>
                            <td>{{ $record->condition }}</td>
                            <td>{{ $record->treatment ?? '-' }}</td>
                            <td>{{ $record->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada data kesehatan</p>
        @endif
    </div>
</body>
</html> 