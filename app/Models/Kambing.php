<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kambing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kambings';

    protected $fillable = [
        'kode_kambing',
        'nama_kambing',
        'jenis_id',
        'jenis_kelamin',
        'tanggal_beli',
        'umur',
        'harga_beli',
        'warna',
        'barn_id',
        'status',
        'keterangan',
        'foto',
        'berat',
        'tanggal_terjual'
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
        'tanggal_terjual' => 'date',
        'harga_beli' => 'decimal:2',
        'berat' => 'decimal:2'
    ];

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }

    public function barn()
    {
        return $this->belongsTo(Barn::class, 'barn_id');
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class, 'goat_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'kambing_id');
    }

    public function sale()
    {
        return $this->hasOne(Sale::class, 'kambing_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->kode_kambing} - {$this->nama_kambing}";
    }
}
