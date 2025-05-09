<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class FeedController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $feeds = Feed::query();
            return DataTables::of($feeds)
                ->addColumn('action', function($feed) {
                    return '
                        <button type="button" class="btn btn-warning btn-sm edit-feed" data-id="'.$feed->id.'" data-toggle="modal" data-target="#editModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm delete-feed" data-id="'.$feed->id.'">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })
                ->editColumn('harga', function($feed) {
                    return 'Rp ' . number_format($feed->harga, 0, ',', '.');
                })
                ->editColumn('tgl_beli', function($feed) {
                    return date('d/m/Y', strtotime($feed->tgl_beli));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('feeds.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_beli' => 'required|date',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric',
            'jumlah_beli' => 'required|integer'
        ]);

        Feed::create($request->all());

        return response()->json(['success' => 'Data pakan/obat berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $feed = Feed::findOrFail($id);
        return response()->json($feed);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_beli' => 'required|date',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric',
            'jumlah_beli' => 'required|integer'
        ]);

        $feed = Feed::findOrFail($id);
        $feed->update($request->all());

        return response()->json(['success' => 'Data pakan/obat berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $feed = Feed::findOrFail($id);
        $feed->delete();

        return response()->json(['success' => 'Data pakan/obat berhasil dihapus']);
    }

    public function exportExcel()
    {
        $filename = 'data-pakan-dan-obat.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $handle = fopen('php://output', 'w');
            
            // Header
            fputcsv($handle, ['No', 'Nama Pakan/Obat', 'Tanggal Beli', 'Satuan', 'Harga', 'Jumlah Beli']);
            
            // Data
            $feeds = Feed::all();
            $no = 1;
            foreach ($feeds as $feed) {
                fputcsv($handle, [
                    $no++,
                    $feed->nama,
                    date('d/m/Y', strtotime($feed->tgl_beli)),
                    $feed->satuan,
                    'Rp ' . number_format($feed->harga, 0, ',', '.'),
                    $feed->jumlah_beli
                ]);
            }
            
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $feeds = Feed::all();
        $pdf = PDF::loadView('feeds.pdf', compact('feeds'));
        return $pdf->download('data-pakan-dan-obat.pdf');
    }
} 