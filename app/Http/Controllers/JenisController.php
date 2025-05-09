<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JenisExport;
use PDF;

class JenisController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $jenis = Jenis::query();
            return DataTables::of($jenis)
                ->addColumn('action', function($jenis) {
                    return '
                        <button type="button" class="btn btn-warning btn-sm edit-jenis" data-id="'.$jenis->id.'" data-toggle="modal" data-target="#editModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm delete-jenis" data-id="'.$jenis->id.'">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('jenis.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kambing' => 'required|string|max:255|unique:jenis'
        ]);

        Jenis::create($request->all());

        return response()->json(['success' => 'Jenis kambing berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $jenis = Jenis::findOrFail($id);
        return response()->json($jenis);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_kambing' => 'required|string|max:255|unique:jenis,jenis_kambing,'.$id
        ]);

        $jenis = Jenis::findOrFail($id);
        $jenis->update($request->all());

        return response()->json(['success' => 'Jenis kambing berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $jenis = Jenis::findOrFail($id);
        $jenis->delete();

        return response()->json(['success' => 'Jenis kambing berhasil dihapus']);
    }

    public function exportExcel()
    {
        $filename = 'jenis-kambing.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $handle = fopen('php://output', 'w');
            
            // Header
            fputcsv($handle, ['No', 'Jenis Kambing']);
            
            // Data
            $jenis = Jenis::all();
            $no = 1;
            foreach ($jenis as $item) {
                fputcsv($handle, [$no++, $item->jenis_kambing]);
            }
            
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $jenis = Jenis::all();
        $pdf = PDF::loadView('jenis.pdf', compact('jenis'));
        return $pdf->download('jenis-kambing.pdf');
    }

    public function getData()
    {
        try {
            \Log::info('Mengambil data jenis kambing');
            $jenis = Jenis::select('id', 'jenis_kambing')->get();
            \Log::info('Data jenis yang ditemukan:', $jenis->toArray());
            
            // Transform data untuk memastikan format yang benar
            $jenis = $jenis->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_jenis' => $item->jenis_kambing
                ];
            });
            
            return response()->json($jenis);
        } catch (\Exception $e) {
            \Log::error('Error in JenisController@getData: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data jenis kambing: ' . $e->getMessage()], 500);
        }
    }
} 