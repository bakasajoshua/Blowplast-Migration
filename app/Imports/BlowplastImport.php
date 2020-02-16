<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BlowplastImport implements WithMultipleSheets 
{
    /**
    * 
    */
    public function sheets(): array
    {
        return [
            'Country' => new CountryImport(),
        ];
    }
}
