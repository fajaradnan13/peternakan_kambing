<?php

namespace App\Exports;

use App\Models\Jenis;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JenisExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Jenis::query();
    }

    public function headings(): array
    {
        return [
            'No',
            'Jenis Kambing',
        ];
    }

    public function map($jenis): array
    {
        static $row = 0;
        $row++;
        return [
            $row,
            $jenis->jenis_kambing,
        ];
    }
} 