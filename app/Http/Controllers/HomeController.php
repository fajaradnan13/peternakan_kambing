<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kambing;
use App\Models\Sale;
use App\Models\Feed;
use App\Models\Jenis;
use App\Models\Barn;
use App\Models\Equipment;
use App\Models\HealthRecord;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Data Kambing
        $totalKambing = Kambing::count();
        $kambingJantan = Kambing::where('jenis_kelamin', 'Jantan')->count();
        $kambingBetina = Kambing::where('jenis_kelamin', 'Betina')->count();
        $kambingTerjual = Kambing::where('status', 'Terjual')->count();
        $kambingAktif = Kambing::where('status', 'Aktif')->count();
        
        // Data Penjualan
        $totalPenjualan = Sale::sum('harga_jual');
        $penjualanBulanIni = Sale::whereMonth('tgl_penjualan', Carbon::now()->month)
                                ->whereYear('tgl_penjualan', Carbon::now()->year)
                                ->sum('harga_jual');
        $jumlahPenjualan = Sale::count();
        
        // Data Pakan
        $totalPakan = Feed::sum('jumlah_beli');
        $pakanHampirHabis = Feed::where('jumlah_beli', '<', 10)->count();
        
        // Data Kandang
        $totalKandang = Barn::count();
        
        // Data Peralatan
        $totalPeralatan = Equipment::count();
        $peralatanRusak = 0; // Kolom kondisi tidak ada, set 0 atau sesuaikan jika ada logika lain
        
        // Data Kesehatan
        $totalRekamMedis = HealthRecord::count();
        $kambingSakit = HealthRecord::where('kondisi_kesehatan', 'Sakit')->count();
        
        // Grafik Penjualan 6 Bulan Terakhir
        $penjualanPerBulan = Sale::select(
            DB::raw('MONTH(tgl_penjualan) as bulan'),
            DB::raw('YEAR(tgl_penjualan) as tahun'),
            DB::raw('SUM(harga_jual) as total')
        )
        ->where('tgl_penjualan', '>=', Carbon::now()->subMonths(6))
        ->groupBy('tahun', 'bulan')
        ->orderBy('tahun')
        ->orderBy('bulan')
        ->get();
        
        // Data Kambing per Jenis
        $kambingPerJenis = Jenis::withCount('kambings')->get();
        
        // Kambing Terbaru
        $kambingTerbaru = Kambing::with('jenis')
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
        
        // Penjualan Terbaru
        $penjualanTerbaru = Sale::with('kambing')
                                ->orderBy('tgl_penjualan', 'desc')
                                ->take(5)
                                ->get();

        // Rekam Medis Terbaru
        $rekamMedisTerbaru = HealthRecord::with('kambing')
                                        ->orderBy('checkup_date', 'desc')
                                        ->take(5)
                                        ->get();

        // LABA RUGI BULAN INI LANGSUNG DARI DATABASE
        $now = Carbon::now();
        $labelBulan = $now->format('Y-m');
        // Penjualan bulan ini
        $penjualanBulanIni = Sale::whereYear('tgl_penjualan', $now->year)
            ->whereMonth('tgl_penjualan', $now->month)
            ->sum('harga_jual');
        // Pembelian kambing bulan ini
        $pembelianKambingBulanIni = Kambing::whereYear('tanggal_beli', $now->year)
            ->whereMonth('tanggal_beli', $now->month)
            ->sum('harga_beli');
        // Pembelian pakan bulan ini
        $pembelianPakanBulanIni = Feed::whereYear('tgl_beli', $now->year)
            ->whereMonth('tgl_beli', $now->month)
            ->selectRaw('COALESCE(SUM(harga * jumlah_beli),0) as total')->value('total');
        // Pembelian alat bulan ini
        $pembelianAlatBulanIni = Equipment::whereYear('tgl_beli', $now->year)
            ->whereMonth('tgl_beli', $now->month)
            ->selectRaw('COALESCE(SUM(harga * jumlah_beli),0) as total')->value('total');
        $labaRugiBulanIni = $penjualanBulanIni - ($pembelianKambingBulanIni + $pembelianPakanBulanIni + $pembelianAlatBulanIni);

        $periode = $request->input('periode', 'bulan');
        if ($periode === 'minggu') {
            $start = $now->copy()->subDays(6)->startOfDay();
            $end = $now->endOfDay();
            $labelPeriode = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');
        } elseif ($periode === 'tahun') {
            $start = $now->copy()->subMonths(11)->startOfMonth();
            $end = $now->endOfMonth();
            $labelPeriode = $start->format('Y') . ' - ' . $end->format('Y');
        } elseif ($periode === 'all') {
            $start = null;
            $end = null;
            $labelPeriode = 'Semua Data';
        } else { // bulan
            $start = $now->copy()->startOfMonth();
            $end = $now->endOfMonth();
            $labelPeriode = $now->format('Y-m');
        }
        // Penjualan
        $penjualan = Sale::when($start, function($q) use ($start) { return $q->where('tgl_penjualan', '>=', $start); })
            ->when($end, function($q) use ($end) { return $q->where('tgl_penjualan', '<=', $end); })
            ->sum('harga_jual');
        // Pembelian kambing
        $pembelianKambing = Kambing::when($start, function($q) use ($start) { return $q->where('tanggal_beli', '>=', $start); })
            ->when($end, function($q) use ($end) { return $q->where('tanggal_beli', '<=', $end); })
            ->sum('harga_beli');
        // Pembelian pakan
        $pembelianPakan = Feed::when($start, function($q) use ($start) { return $q->where('tgl_beli', '>=', $start); })
            ->when($end, function($q) use ($end) { return $q->where('tgl_beli', '<=', $end); })
            ->selectRaw('COALESCE(SUM(harga * jumlah_beli),0) as total')->value('total');
        // Pembelian alat
        $pembelianAlat = Equipment::when($start, function($q) use ($start) { return $q->where('tgl_beli', '>=', $start); })
            ->when($end, function($q) use ($end) { return $q->where('tgl_beli', '<=', $end); })
            ->selectRaw('COALESCE(SUM(harga * jumlah_beli),0) as total')->value('total');
        $labaRugi = $penjualan - ($pembelianKambing + $pembelianPakan + $pembelianAlat);

        $barnsInfo = Barn::select('id', 'name', 'kapasitas', 'status', 'kondisi', 'catatan')->get();

        return view('home', compact(
            'totalKambing', 'kambingJantan', 'kambingBetina', 'kambingTerjual', 'kambingAktif',
            'totalPenjualan', 'penjualanBulanIni', 'jumlahPenjualan',
            'totalPakan', 'pakanHampirHabis',
            'totalKandang',
            'totalPeralatan', 'peralatanRusak',
            'totalRekamMedis', 'kambingSakit',
            'penjualanPerBulan', 'kambingPerJenis',
            'kambingTerbaru', 'penjualanTerbaru',
            'rekamMedisTerbaru',
            'labelBulan', 'penjualanBulanIni', 'pembelianKambingBulanIni', 'pembelianPakanBulanIni', 'pembelianAlatBulanIni', 'labaRugiBulanIni',
            'labelPeriode', 'periode', 'penjualan', 'pembelianKambing', 'pembelianPakan', 'pembelianAlat', 'labaRugi',
            'barnsInfo'
        ));
    }

    public function labaRugi(Request $request)
    {
        $range = $request->input('range', 'bulan');
        $now = Carbon::now();
        $start = match($range) {
            'minggu' => $now->copy()->subDays(6)->startOfDay(),
            'tahun' => $now->copy()->subMonths(11)->startOfMonth(),
            default => $now->copy()->subMonths(5)->startOfMonth(),
        };
        $end = $now->endOfDay();

        // Penjualan Kambing
        $penjualan = Sale::whereBetween('tgl_penjualan', [$start, $end])
            ->select(
                DB::raw('DATE_FORMAT(tgl_penjualan, "' . ($range == 'minggu' ? '%Y-%m-%d' : ($range == 'tahun' ? '%Y-%m' : '%Y-%m')) . '") as label'),
                DB::raw('SUM(harga_jual) as total')
            )
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        // Pembelian Kambing
        $pembelianKambing = Kambing::whereBetween('tanggal_beli', [$start, $end])
            ->select(
                DB::raw('DATE_FORMAT(tanggal_beli, "' . ($range == 'minggu' ? '%Y-%m-%d' : ($range == 'tahun' ? '%Y-%m' : '%Y-%m')) . '") as label'),
                DB::raw('SUM(harga_beli) as total')
            )
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        // Pembelian Pakan
        $pembelianPakan = Feed::whereBetween('tgl_beli', [$start, $end])
            ->select(
                DB::raw('DATE_FORMAT(tgl_beli, "' . ($range == 'minggu' ? '%Y-%m-%d' : ($range == 'tahun' ? '%Y-%m' : '%Y-%m')) . '") as label'),
                DB::raw('SUM(harga * jumlah_beli) as total')
            )
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        // Pembelian Alat/Perlengkapan
        $pembelianAlat = Equipment::whereBetween('tgl_beli', [$start, $end])
            ->select(
                DB::raw('DATE_FORMAT(tgl_beli, "' . ($range == 'minggu' ? '%Y-%m-%d' : ($range == 'tahun' ? '%Y-%m' : '%Y-%m')) . '") as label'),
                DB::raw('SUM(harga * jumlah_beli) as total')
            )
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        // Gabungkan label
        $labels = collect($penjualan)->pluck('label')
            ->merge(collect($pembelianKambing)->pluck('label'))
            ->merge(collect($pembelianPakan)->pluck('label'))
            ->merge(collect($pembelianAlat)->pluck('label'))
            ->unique()->sort()->values();

        $result = $labels->map(function($label) use ($penjualan, $pembelianKambing, $pembelianPakan, $pembelianAlat) {
            $jual = collect($penjualan)->firstWhere('label', $label)?->total ?? 0;
            $beliKambing = collect($pembelianKambing)->firstWhere('label', $label)?->total ?? 0;
            $beliPakan = collect($pembelianPakan)->firstWhere('label', $label)?->total ?? 0;
            $beliAlat = collect($pembelianAlat)->firstWhere('label', $label)?->total ?? 0;
            $labaRugi = $jual - ($beliKambing + $beliPakan + $beliAlat);
            return [
                'label' => $label,
                'penjualan' => $jual,
                'pembelian_kambing' => $beliKambing,
                'pembelian_pakan' => $beliPakan,
                'pembelian_alat' => $beliAlat,
                'laba_rugi' => $labaRugi
            ];
        });

        // Setelah $result dibuat, tambahkan log
        \Log::info('LabaRugi Debug', ['result' => $result]);
        return response()->json($result);
    }
}
