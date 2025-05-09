<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kambing;
use App\Models\Jenis;
use App\Models\Barn;
use DataTables;
use PDF;

class KambingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            try {
                $kambings = Kambing::with(['jenis', 'barn'])->get();
                return DataTables::of($kambings)
                    ->addIndexColumn()
                    ->addColumn('foto', function($kambing) {
                        if ($kambing->foto) {
                            return '<img src="'.asset($kambing->foto).'" alt="Foto Kambing" style="max-width: 100px;">';
                        }
                        return 'Tidak ada foto';
                    })
                    ->addColumn('jenis_kambing', function($kambing) {
                        return $kambing->jenis ? $kambing->jenis->jenis_kambing : '-';
                    })
                    ->addColumn('kandang', function($kambing) {
                        return $kambing->barn ? $kambing->barn->name : '-';
                    })
                    ->addColumn('action', function($kambing) {
                        return '
                            <button type="button" class="btn btn-info btn-sm show-kambing" data-id="'.$kambing->id.'" data-toggle="modal" data-target="#showModal">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm edit-kambing" data-id="'.$kambing->id.'" data-toggle="modal" data-target="#editModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm delete-kambing" data-id="'.$kambing->id.'">
                                <i class="fas fa-trash"></i>
                            </button>
                        ';
                    })
                    ->editColumn('tanggal_beli', function($kambing) {
                        return $kambing->tanggal_beli ? $kambing->tanggal_beli->format('d/m/Y') : '-';
                    })
                    ->editColumn('harga_beli', function($kambing) {
                        return 'Rp ' . number_format($kambing->harga_beli, 0, ',', '.');
                    })
                    ->rawColumns(['foto', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error('Error in KambingController@index: ' . $e->getMessage());
                return response()->json(['error' => 'Terjadi kesalahan saat mengambil data'], 500);
            }
        }
        $jenis = Jenis::all();
        $barns = Barn::all();
        return view('kambing.index', compact('jenis', 'barns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenis = Jenis::all();
        $barns = Barn::all();
        return view('kambing.create', compact('jenis', 'barns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_kambing' => 'required',
                'jenis_id' => 'required',
                'jenis_kelamin' => 'required',
                'tanggal_beli' => 'required|date',
                'umur' => 'required|integer|min:0',
                'harga_beli' => 'required|numeric|min:0',
                'warna' => 'required',
                'barn_id' => 'required|exists:barns,id',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'keterangan' => 'nullable'
            ]);

            $data = [
                'kode_kambing' => $this->generateKodeKambing(),
                'nama_kambing' => $request->nama_kambing,
                'jenis_id' => $request->jenis_id,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_beli' => $request->tanggal_beli,
                'umur' => $request->umur,
                'harga_beli' => $request->harga_beli,
                'warna' => $request->warna,
                'barn_id' => $request->barn_id,
                'keterangan' => $request->keterangan,
                'status' => 'Ternak'
            ];

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $nama_foto = time() . '_' . $foto->getClientOriginalName();
                $foto->move(public_path('uploads/kambing'), $nama_foto);
                $data['foto'] = 'uploads/kambing/' . $nama_foto;
            }

            Kambing::create($data);

            // Kurangi kapasitas barn dan update catatan kapasitas
            $barn = Barn::find($request->barn_id);
            if ($barn) {
                $jumlahKambing = $barn->kambings()->count();
                $tersedia = max(0, $barn->kapasitas - $jumlahKambing);
                $barn->catatan = $barn->kapasitas . '/' . $tersedia;
                // Jika sudah penuh, update status
                $barn->status = $tersedia == 0 ? 'penuh' : 'tersedia';
                $barn->save();
            }

            return response()->json(['success' => 'Data kambing berhasil ditambahkan']);
        } catch (\Exception $e) {
            \Log::error('Error in KambingController@store: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $kambing = Kambing::with(['jenis', 'barn', 'healthRecords' => function($query) {
                $query->orderBy('checkup_date', 'desc');
            }])->findOrFail($id);

            // Hitung umur saat ini
            try {
                $tanggalBeli = new \DateTime($kambing->tanggal_beli);
                $sekarang = new \DateTime();
                $selisihHari = $sekarang->diff($tanggalBeli)->days;
                
                $totalBulan = $kambing->umur + floor($selisihHari / 30);
                $sisaHari = $selisihHari % 30;
                
                $umurSaatIni = $sisaHari === 0 ? 
                    $totalBulan . ' bulan' : 
                    $totalBulan . ' bulan, ' . $sisaHari . ' hari';
            } catch (\Exception $e) {
                \Log::error('Error menghitung umur: ' . $e->getMessage());
                $umurSaatIni = 'Tidak dapat menghitung umur';
            }

            $data = [
                'success' => true,
                'kambing' => [
                    'id' => $kambing->id,
                    'kode_kambing' => $kambing->kode_kambing,
                    'nama_kambing' => $kambing->nama_kambing,
                    'jenis_kelamin' => $kambing->jenis_kelamin,
                    'tanggal_beli' => $kambing->tanggal_beli,
                    'umur' => $kambing->umur,
                    'harga_beli' => $kambing->harga_beli,
                    'warna' => $kambing->warna,
                    'status' => $kambing->status,
                    'keterangan' => $kambing->keterangan,
                    'foto' => $kambing->foto ? asset($kambing->foto) : null,
                    'barn_id' => $kambing->barn_id
                ],
                'jenis' => $kambing->jenis ? $kambing->jenis->jenis_kambing : '-',
                'barn' => $kambing->barn ? $kambing->barn->name : '-',
                'health_records' => $kambing->healthRecords,
                'umur_saat_ini' => $umurSaatIni
            ];

            return response()->json($data, 200);

        } catch (\Exception $e) {
            \Log::error('Error di KambingController@show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kambing',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $kambing = Kambing::with(['jenis', 'barn'])->findOrFail($id);
            $jenis = Jenis::select('id', 'jenis_kambing as nama_jenis')->get();
            $barns = Barn::select('id', 'name')->get();
            
            return response()->json([
                'kambing' => $kambing,
                'jenis' => $jenis,
                'barns' => $barns
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in KambingController@edit: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat data kambing: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_kambing' => 'required',
                'jenis_id' => 'required',
                'jenis_kelamin' => 'required',
                'tanggal_beli' => 'required|date',
                'umur' => 'required|integer|min:0',
                'harga_beli' => 'required|numeric|min:0',
                'warna' => 'required',
                'barn_id' => 'required|exists:barns,id',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'keterangan' => 'nullable'
            ]);

            $kambing = Kambing::findOrFail($id);
            
            $data = [
                'nama_kambing' => $request->nama_kambing,
                'jenis_id' => $request->jenis_id,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_beli' => $request->tanggal_beli,
                'umur' => $request->umur,
                'harga_beli' => $request->harga_beli,
                'warna' => $request->warna,
                'barn_id' => $request->barn_id,
                'keterangan' => $request->keterangan,
                'status' => $request->status ?? $kambing->status
            ];

            if ($request->hasFile('foto')) {
                if ($kambing->foto && file_exists(public_path($kambing->foto))) {
                    unlink(public_path($kambing->foto));
                }
                
                $foto = $request->file('foto');
                $nama_foto = time() . '_' . $foto->getClientOriginalName();
                $foto->move(public_path('uploads/kambing'), $nama_foto);
                $data['foto'] = 'uploads/kambing/' . $nama_foto;
            }

            $kambing->update($data);

            return response()->json(['success' => 'Data kambing berhasil diperbarui']);
        } catch (\Exception $e) {
            \Log::error('Error in KambingController@update: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $kambing = Kambing::findOrFail($id);
            if ($kambing->foto && file_exists(public_path($kambing->foto))) {
                unlink(public_path($kambing->foto));
            }
            $kambing->delete();

            return response()->json(['success' => 'Data kambing berhasil dihapus']);
        } catch (\Exception $e) {
            \Log::error('Error in KambingController@destroy: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()], 500);
        }
    }

    private function generateKodeKambing()
    {
        try {
            $lastKambing = Kambing::orderBy('id', 'desc')->first();
            $number = $lastKambing ? intval(substr($lastKambing->kode_kambing, 3)) + 1 : 1;
            return 'KB-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        } catch (\Exception $e) {
            \Log::error('Error in generateKodeKambing: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getNextKode()
    {
        try {
            $kode = $this->generateKodeKambing();
            return response()->json(['kode_kambing' => $kode]);
        } catch (\Exception $e) {
            \Log::error('Error in getNextKode: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mendapatkan kode kambing: ' . $e->getMessage()], 500);
        }
    }

    public function exportCsv()
    {
        $filename = 'data-kambing.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Kode Kambing', 'Nama Kambing', 'Jenis Kambing', 'Jenis Kelamin', 'Tanggal Beli', 'Umur (bulan)', 'Harga Beli', 'Warna', 'Kandang', 'Keterangan']);
            
            $kambings = Kambing::with(['jenis', 'barn'])->get();
            $no = 1;
            
            foreach ($kambings as $kambing) {
                fputcsv($handle, [
                    $no++,
                    $kambing->kode_kambing,
                    $kambing->nama_kambing,
                    $kambing->jenis ? $kambing->jenis->jenis_kambing : '-',
                    $kambing->jenis_kelamin,
                    $kambing->tanggal_beli,
                    $kambing->umur,
                    $kambing->harga_beli,
                    $kambing->warna,
                    $kambing->barn ? $kambing->barn->name : '-',
                    $kambing->keterangan ?? '-'
                ]);
            }
            
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf($id)
    {
        $kambing = Kambing::with(['jenis', 'barn', 'healthRecords'])->findOrFail($id);
        
        // Hitung umur saat ini
        $tanggalBeli = new \DateTime($kambing->tanggal_beli);
        $sekarang = new \DateTime();
        $selisihHari = $sekarang->diff($tanggalBeli)->days;
        
        $totalBulan = $kambing->umur + floor($selisihHari / 30);
        $sisaHari = $selisihHari % 30;
        
        if ($sisaHari === 0) {
            $umurSaatIni = $totalBulan . ' bulan';
        } else {
            $umurSaatIni = $totalBulan . ' bulan, ' . $sisaHari . ' hari';
        }

        // Pastikan data yang diperlukan tersedia
        if (!$kambing->jenis) {
            $kambing->load('jenis');
        }
        if (!$kambing->barn) {
            $kambing->load('barn');
        }
        if (!$kambing->healthRecords) {
            $kambing->load('healthRecords');
        }
        
        $pdf = PDF::loadView('kambing.pdf', compact('kambing', 'umurSaatIni'));
        return $pdf->download('detail-kambing-' . $kambing->kode_kambing . '.pdf');
    }

    public function exportDetailPdf($id)
    {
        $kambing = Kambing::with(['jenis', 'barn', 'healthRecords'])->findOrFail($id);
        $pdf = PDF::loadView('kambing.detail-pdf', compact('kambing'));
        return $pdf->download('detail-kambing-' . $kambing->kode_kambing . '.pdf');
    }
}
