<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class EquipmentController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $equipments = Equipment::query();
            return DataTables::of($equipments)
                ->addColumn('action', function($equipment) {
                    return '
                        <button type="button" class="btn btn-warning btn-sm edit-equipment" data-id="'.$equipment->id.'" data-toggle="modal" data-target="#editModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm delete-equipment" data-id="'.$equipment->id.'">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })
                ->editColumn('harga', function($equipment) {
                    return 'Rp ' . number_format($equipment->harga, 0, ',', '.');
                })
                ->editColumn('tgl_beli', function($equipment) {
                    return date('d/m/Y', strtotime($equipment->tgl_beli));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('equipments.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:Peralatan,Perlengkapan Kandang',
            'tgl_beli' => 'required|date',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric',
            'jumlah_beli' => 'required|integer',
            'keterangan' => 'nullable|string'
        ]);

        Equipment::create($request->all());

        return response()->json(['success' => 'Data alat dan perlengkapan berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        return response()->json($equipment);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:Peralatan,Perlengkapan Kandang',
            'tgl_beli' => 'required|date',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric',
            'jumlah_beli' => 'required|integer',
            'keterangan' => 'nullable|string'
        ]);

        $equipment = Equipment::findOrFail($id);
        $equipment->update($request->all());

        return response()->json(['success' => 'Data alat dan perlengkapan berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();

        return response()->json(['success' => 'Data alat dan perlengkapan berhasil dihapus']);
    }

    public function exportExcel()
    {
        $filename = 'data-alat-dan-perlengkapan.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $handle = fopen('php://output', 'w');
            
            // Header
            fputcsv($handle, ['No', 'Nama', 'Kategori', 'Tanggal Beli', 'Satuan', 'Harga', 'Jumlah Beli', 'Keterangan']);
            
            // Data
            $equipments = Equipment::all();
            $no = 1;
            foreach ($equipments as $equipment) {
                fputcsv($handle, [
                    $no++,
                    $equipment->nama,
                    $equipment->kategori,
                    date('d/m/Y', strtotime($equipment->tgl_beli)),
                    $equipment->satuan,
                    'Rp ' . number_format($equipment->harga, 0, ',', '.'),
                    $equipment->jumlah_beli,
                    $equipment->keterangan
                ]);
            }
            
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $equipments = Equipment::all();
        $pdf = PDF::loadView('equipments.pdf', compact('equipments'));
        return $pdf->download('data-alat-dan-perlengkapan.pdf');
    }
} 