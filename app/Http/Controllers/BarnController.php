<?php

namespace App\Http\Controllers;

use App\Models\Barn;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class BarnController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $barns = Barn::query();
            return DataTables::of($barns)
                ->addColumn('action', function($barn) {
                    return '
                        <button type="button" class="btn btn-info btn-sm show-barn" data-id="'.$barn->id.'" data-toggle="modal" data-target="#showModal"><i class="fas fa-eye"></i></button>
                        <button type="button" class="btn btn-warning btn-sm edit-barn" data-id="'.$barn->id.'" data-toggle="modal" data-target="#editModal"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm delete-barn" data-id="'.$barn->id.'"><i class="fas fa-trash"></i></button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('barn.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
        ]);
        Barn::create($request->all());
        return response()->json(['success' => 'Data kandang berhasil ditambahkan']);
    }

    public function show($id)
    {
        $barn = Barn::findOrFail($id);
        return response()->json($barn);
    }

    public function edit($id)
    {
        $barn = Barn::findOrFail($id);
        return response()->json($barn);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
        ]);
        $barn = Barn::findOrFail($id);
        $barn->update($request->all());
        return response()->json(['success' => 'Data kandang berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $barn = Barn::findOrFail($id);
        $barn->delete();
        return response()->json(['success' => 'Data kandang berhasil dihapus']);
    }

    public function exportCsv()
    {
        $filename = 'data-kandang.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        $callback = function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Nama Kandang', 'Lokasi']);
            $barns = Barn::all();
            $no = 1;
            foreach ($barns as $barn) {
                fputcsv($handle, [$no++, $barn->name, $barn->location]);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $barns = Barn::all();
        $pdf = PDF::loadView('barn.pdf', compact('barns'));
        return $pdf->download('data-kandang.pdf');
    }
} 