<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class GLAccountsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->getAccounts();
    }

    private function getAccounts()
    {
    	$gl = new \App\GLAccounts;
    	return $gl->fetchData();
    }
}
