<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthRecord;
use App\Models\Kambing;
use DataTables;
use PDF;

class HealthRecordController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $healthRecords = HealthRecord::with('kambing')->get();
            return DataTables::of($healthRecords)
                ->addIndexColumn()
                ->addColumn('kode_kambing', function($record) {
                    return $record->kambing ? $record->kambing->kode_kambing : '-';
                })
                ->addColumn('nama_kambing', function($record) {
                    return $record->kambing ? $record->kambing->nama_kambing : '-';
                })
                ->addColumn('action', function($record) {
                    return '
                        <button type="button" class="btn btn-info btn-sm show-record" data-id="'.$record->id.'" data-toggle="modal" data-target="#showModal">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-sm edit-record" data-id="'.$record->id.'" data-toggle="modal" data-target="#editModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm delete-record" data-id="'.$record->id.'">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $kambings = Kambing::select('id', 'kode_kambing', 'nama_kambing')->get();
        return view('health_record.index', compact('kambings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'goat_id' => 'required|exists:kambings,id',
            'checkup_date' => 'required|date',
            'condition' => 'required|string',
            'kondisi_kesehatan' => 'required|in:Sehat,Sakit',
            'kehamilan' => 'required|in:0,1',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['kehamilan'] = (bool)$request->input('kehamilan');

        HealthRecord::create($data);

        return response()->json(['success' => 'Data kesehatan kambing berhasil ditambahkan']);
    }

    public function show($id)
    {
        $record = HealthRecord::with('kambing')->findOrFail($id);
        return response()->json($record);
    }

    public function edit($id)
    {
        $record = HealthRecord::findOrFail($id);
        $kambings = Kambing::select('id', 'kode_kambing', 'nama_kambing')->get();
        return response()->json([
            'record' => $record,
            'kambings' => $kambings
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'goat_id' => 'required|exists:kambings,id',
            'checkup_date' => 'required|date',
            'condition' => 'required|string',
            'kondisi_kesehatan' => 'required|in:Sehat,Sakit',
            'kehamilan' => 'required|in:0,1',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $record = HealthRecord::findOrFail($id);
        $data = $request->all();
        $data['kehamilan'] = (bool)$request->input('kehamilan');
        $record->update($data);

        return response()->json(['success' => 'Data kesehatan kambing berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $record = HealthRecord::findOrFail($id);
        $record->delete();

        return response()->json(['success' => 'Data kesehatan kambing berhasil dihapus']);
    }

    public function exportCsv()
    {
        $filename = 'data-kesehatan-kambing.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Kode Kambing', 'Nama Kambing', 'Tanggal Pemeriksaan', 'Kondisi', 'Perawatan', 'Catatan']);
            
            $records = HealthRecord::with('kambing')->get();
            $no = 1;
            
            foreach ($records as $record) {
                fputcsv($handle, [
                    $no++,
                    $record->kambing ? $record->kambing->kode_kambing : '-',
                    $record->kambing ? $record->kambing->nama_kambing : '-',
                    $record->checkup_date,
                    $record->condition,
                    $record->treatment ?? '-',
                    $record->notes ?? '-'
                ]);
            }
            
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $records = HealthRecord::with('kambing')->get();
        $pdf = PDF::loadView('health_record.pdf', compact('records'));
        return $pdf->download('data-kesehatan-kambing.pdf');
    }
} 