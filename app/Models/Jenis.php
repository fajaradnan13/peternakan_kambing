<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    use HasFactory;

    protected $table = 'jenis';
    protected $fillable = ['jenis_kambing'];

    public function kambings()
    {
        return $this->hasMany(Kambing::class);
    }

    // Accessor untuk mendapatkan nama jenis
    public function getNamaJenisAttribute()
    {
        return $this->jenis_kambing;
    }
} 