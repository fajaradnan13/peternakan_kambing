<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Kambing;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use PDF;
use DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Sale::with('kambing')->orderByDesc('tgl_penjualan');

        // Pencarian
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('kode_transaksi', 'like', "%$q%")
                    ->orWhere('pembeli', 'like', "%$q%")
                    ->orWhereHas('kambing', function($k) use ($q) {
                        $k->where('nama_kambing', 'like', "%$q%")
                            ->orWhere('kode_kambing', 'like', "%$q%")
                            ->orWhere('jenis_kelamin', 'like', "%$q%")
                            ->orWhere('warna', 'like', "%$q%")
                            ->orWhere('status', 'like', "%$q%")
                            ->orWhere('keterangan', 'like', "%$q%")
                            ;
                    });
            });
        }

        $sales = $query->paginate(10)->withQueryString();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $kode_transaksi = $this->getNextKode();
        $kambings = Kambing::whereDoesntHave('sale')->get();
        return view('sales.create', compact('kambings', 'kode_transaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_penjualan' => 'required|date',
            'kambing_id' => 'required|exists:kambings,id',
            'harga_jual' => 'required|numeric|min:0',
            'pembeli' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        $sale = new Sale();
        $sale->kode_transaksi = $this->getNextKode();
        $sale->tgl_penjualan = $request->tgl_penjualan;
        $sale->kambing_id = $request->kambing_id;
        $sale->harga_jual = $request->harga_jual;
        $sale->pembeli = $request->pembeli;
        $sale->keterangan = $request->keterangan;
        $sale->save();

        // Update status kambing menjadi Terjual dan set umur saat penjualan
        $kambing = Kambing::find($request->kambing_id);
        if ($kambing) {
            $umur = $kambing->umur;
            // Hitung umur saat penjualan jika tanggal_beli ada
            if ($kambing->tanggal_beli && $request->tgl_penjualan) {
                $umur = \Carbon\Carbon::parse($kambing->tanggal_beli)->diffInMonths($request->tgl_penjualan);
            }
            $kambing->status = 'Terjual';
            $kambing->umur = $umur;
            $kambing->tanggal_terjual = $request->tgl_penjualan;
            $kambing->save();
        }

        return redirect()->route('sales.index')
            ->with('success', 'Data penjualan berhasil ditambahkan');
    }

    public function show($id)
    {
        $sale = Sale::with('kambing.jenis')->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    public function edit($id)
    {
        $sale = Sale::findOrFail($id);
        $kambings = Kambing::whereDoesntHave('sale')
            ->orWhere('id', $sale->kambing_id)
            ->get();
        return view('sales.edit', compact('sale', 'kambings'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_penjualan' => 'required|date',
            'kambing_id' => 'required|exists:kambings,id',
            'harga_jual' => 'required|numeric|min:0',
            'pembeli' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        $sale = Sale::findOrFail($id);
        $sale->tgl_penjualan = $request->tgl_penjualan;
        $sale->kambing_id = $request->kambing_id;
        $sale->harga_jual = $request->harga_jual;
        $sale->pembeli = $request->pembeli;
        $sale->keterangan = $request->keterangan;
        $sale->save();

        // Update status kambing menjadi Terjual dan set umur saat penjualan
        $kambing = Kambing::find($request->kambing_id);
        if ($kambing) {
            $umur = $kambing->umur;
            if ($kambing->tanggal_beli && $request->tgl_penjualan) {
                $umur = \Carbon\Carbon::parse($kambing->tanggal_beli)->diffInMonths($request->tgl_penjualan);
            }
            $kambing->status = 'Terjual';
            $kambing->umur = $umur;
            $kambing->tanggal_terjual = $request->tgl_penjualan;
            $kambing->save();
        }

        return redirect()->route('sales.index')
            ->with('success', 'Data penjualan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return response()->json([
            'success' => 'Data penjualan berhasil dihapus'
        ]);
    }

    public function getData()
    {
        $sales = Sale::with('kambing')->get();
        $data = [];
        $no = 1;
        foreach ($sales as $sale) {
            $data[] = [
                'no' => $no++,
                'kode_transaksi' => $sale->kode_transaksi,
                'tgl_penjualan' => $sale->tgl_penjualan ? $sale->tgl_penjualan->format('d/m/Y') : '-',
                'nama_kambing' => $sale->kambing ? $sale->kambing->nama_kambing : '-',
                'harga_jual' => 'Rp ' . number_format($sale->harga_jual, 0, ',', '.'),
                'pembeli' => $sale->pembeli,
                'action' => '<button class="btn btn-sm btn-info btn-show" data-id="' . $sale->id . '"><i class="fas fa-eye"></i></button>
                    <a href="' . route('sales.edit', $sale->id) . '" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="' . $sale->id . '"><i class="fas fa-trash"></i></button>'
            ];
        }
        return response()->json(['data' => $data]);
    }

    public function getNextKode()
    {
        $lastSale = Sale::orderBy('id', 'desc')->first();
        $lastNumber = $lastSale ? intval(substr($lastSale->kode_transaksi, 3)) : 0;
        $nextNumber = $lastNumber + 1;
        return 'TRX' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function exportCsv()
    {
        $sales = Sale::with('kambing.jenis')->get();
        $filename = 'penjualan_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Kode Transaksi',
                'Tanggal',
                'Kode Kambing',
                'Nama Kambing',
                'Jenis',
                'Harga Jual',
                'Pembeli',
                'Keterangan'
            ]);

            // Data
            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->kode_transaksi,
                    $sale->tgl_penjualan->format('d/m/Y'),
                    $sale->kambing->kode_kambing,
                    $sale->kambing->nama_kambing,
                    $sale->kambing->jenis->nama_jenis,
                    $sale->harga_jual,
                    $sale->pembeli,
                    $sale->keterangan
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $sales = Sale::with('kambing.jenis')->get();
        $pdf = PDF::loadView('sales.pdf', compact('sales'));
        return $pdf->download('penjualan_' . date('Y-m-d') . '.pdf');
    }
} 