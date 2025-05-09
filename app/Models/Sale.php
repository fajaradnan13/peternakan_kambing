<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'kode_transaksi',
        'tgl_penjualan',
        'kambing_id',
        'harga_jual',
        'pembeli',
        'keterangan'
    ];

    protected $casts = [
        'tgl_penjualan' => 'date'
    ];

    public function kambing()
    {
        return $this->belongsTo(Kambing::class, 'kambing_id');
    }
} 