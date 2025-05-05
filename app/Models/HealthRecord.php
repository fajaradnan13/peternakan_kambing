<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $table = 'health_records';

    protected $fillable = [
        'goat_id',
        'checkup_date',
        'condition',
        'kondisi_kesehatan',
        'kehamilan',
        'treatment',
        'notes'
    ];

    protected $casts = [
        'checkup_date' => 'date',
    ];

    public function kambing()
    {
        return $this->belongsTo(Kambing::class, 'goat_id');
    }
} 