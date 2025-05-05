<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kambing extends Model
{
    protected $fillable = [
        'kode_kambing',
        'nama_kambing',
        'jenis_id',
        'jenis_kelamin',
        'tanggal_beli',
        'umur',
        'harga_beli',
        'warna',
        'foto',
        'keterangan',
        'barn_id'
    ];

    public function jenis()
    {
        return $this->belongsTo(Jenis::class);
    }

    public function barn()
    {
        return $this->belongsTo(Barn::class);
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class, 'goat_id');
    }
}
