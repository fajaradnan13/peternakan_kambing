<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipments';

    protected $fillable = [
        'nama',
        'kategori',
        'tgl_beli',
        'satuan',
        'harga',
        'jumlah_beli',
        'keterangan'
    ];
} 