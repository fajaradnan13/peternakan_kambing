<!DOCTYPE html>
<html>
<head>
    <title>Laporan Detail Kambing</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 30px 40px;
            color: #222;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            font-size: 2rem;
            letter-spacing: 2px;
            color: #2c3e50;
        }
        .header small {
            color: #888;
            font-size: 1rem;
        }
        .info-section {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
        }
        .info-photo {
            flex: 0 0 200px;
            text-align: center;
        }
        .info-photo img {
            max-width: 180px;
            max-height: 180px;
            border-radius: 8px;
            border: 2px solid #e1e1e1;
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
        }
        .info-table {
            flex: 1;
        }
        .info-table table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
        }
        .info-table th, .info-table td {
            padding: 8px 12px;
            border: 1px solid #e1e1e1;
        }
        .info-table th {
            background: #f7fafd;
            color: #2c3e50;
            width: 35%;
            text-align: left;
        }
        .info-table td {
            background: #fff;
        }
        .section-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #2980b9;
            margin: 30px 0 10px 0;
            border-left: 5px solid #2980b9;
            padding-left: 10px;
        }
        .health-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.98rem;
        }
        .health-table th, .health-table td {
            border: 1px solid #e1e1e1;
            padding: 7px 10px;
        }
        .health-table th {
            background: #f7fafd;
            color: #2c3e50;
        }
        .health-table tr:nth-child(even) td {
            background: #f9f9f9;
        }
        .no-data {
            color: #888;
            font-style: italic;
            text-align: center;
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Detail Kambing</h2>
        <small>Dicetak pada: {{ date('d/m/Y H:i') }}</small>
    </div>

    <div class="info-section">
        <div class="info-photo">
            @if($kambing->foto)
                <img src="{{ public_path($kambing->foto) }}" alt="Foto Kambing">
            @else
                <div class="no-data">Tidak ada foto</div>
            @endif
        </div>
        <div class="info-table">
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
                    <th>Tanggal Beli</th>
                    <td>{{ \Carbon\Carbon::parse($kambing->tanggal_beli)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Umur Saat Beli</th>
                    <td>{{ $kambing->umur }} bulan</td>
                </tr>
                <tr>
                    <th>Umur Saat Ini</th>
                    <td>{{ $umurSaatIni }}</td>
                </tr>
                <tr>
                    <th>Harga Beli</th>
                    <td>Rp {{ number_format($kambing->harga_beli, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Warna</th>
                    <td>{{ $kambing->warna }}</td>
                </tr>
                <tr>
                    <th>Kandang</th>
                    <td>{{ $kambing->barn ? $kambing->barn->name : '-' }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $kambing->keterangan ?: '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section-title">Riwayat Kesehatan</div>
    @if($kambing->healthRecords->count() > 0)
        <table class="health-table">
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
                @foreach($kambing->healthRecords->sortByDesc('checkup_date') as $record)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($record->checkup_date)->format('d/m/Y') }}</td>
                        <td>{{ $record->kondisi_kesehatan }}</td>
                        <td>{{ $record->kehamilan ? 'Ya' : 'Tidak' }}</td>
                        <td>{{ $record->condition }}</td>
                        <td>{{ $record->treatment ?: '-' }}</td>
                        <td>{{ $record->notes ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada data kesehatan</div>
    @endif
</body>
</html> 