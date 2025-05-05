<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barn extends Model
{
    protected $table = 'barns';
    protected $fillable = [
        'name',
        'location',
    ];

    public function kambings()
    {
        return $this->hasMany(Kambing::class);
    }
} 