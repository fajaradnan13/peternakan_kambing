<?php

namespace App\Http\Controllers;

use App\Models\Kambing;
use App\Models\HealthRecord;
use Illuminate\Http\Request;

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
    public function index()
    {
        // Total Kambing
        $totalKambing = Kambing::count();

        // Kambing Sehat dan Sakit berdasarkan kondisi kesehatan terakhir
        $kambingSehat = 0;
        $kambingSakit = 0;
        
        // Ambil data kambing dengan health record terakhir
        $kambings = Kambing::with(['healthRecords' => function($query) {
            $query->orderBy('checkup_date', 'desc')->limit(1);
        }])->get();

        foreach ($kambings as $kambing) {
            if ($kambing->healthRecords->isNotEmpty()) {
                $latestHealth = $kambing->healthRecords->first();
                if ($latestHealth->kondisi_kesehatan === 'Sehat') {
                    $kambingSehat++;
                } else {
                    $kambingSakit++;
                }
            } else {
                // Jika tidak ada data kesehatan, dianggap sehat
                $kambingSehat++;
            }
        }

        // Total Kambing Hamil berdasarkan data terakhir
        $kambingHamil = HealthRecord::where('kehamilan', true)
            ->whereIn('id', function($query) {
                $query->selectRaw('MAX(id)')
                    ->from('health_records')
                    ->groupBy('goat_id');
            })
            ->count();

        return view('home', compact(
            'totalKambing',
            'kambingSehat',
            'kambingSakit',
            'kambingHamil'
        ));
    }
}
