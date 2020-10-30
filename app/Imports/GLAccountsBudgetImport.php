<?php

namespace App\Imports;

use App\GLAccountsBudget;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GLAccountsBudgetImport implements ToModel, WithHeadingRow, WithProgressBar
{
    use Importable;

    private $months = [
    		'january' => '01',
    		'february' => '02',
    		"march" => '03',
			"april" => '04',
			"may" => '05',
			"june" => '06',
			"july" => '07',
			"august" => '08',
			"september" => '09',
			"october" => '10',
			"november" => '11',
			"december" => '12',
    	];

   	private $company = NULL;
    
    public function __construct($company = 'BPL')
    {
    	$this->company = $company;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
    	$budget_month = '';
    	$data = [];
    	foreach ($this->months as $key => $month) {
    		$budget_month = $row['per'] . '/' . $month;
    		$budget_item = GLAccountsBudget::create([
	        	'GL_Account_Budget_No' => $row['srt'],
				'GL_Account_No' =>  NULL,
				'GL_Account_Name' => $row['type'],
				'Budget_Value_Stream' => $row['value'],
				'Company_Code' => $this->company,
				'Budget_Year' => $row['per'],
				'Budget_Month' => $budget_month,
				'Budget_Amount_Excluding_Tax' => $row[$key],
				'Budget_Amount_Including_Tax' => $row[$key],
	        ]);
    	}
    	// $amount = $this->getMonthValue($row);
    	// dd($data);
    	// dd();
        return $budget_item;
  //       GL_Account_Budget_No
		// GL_Account_No
		// GL_Account_Name
		// Budget_Value_Stream
		// Company_Code
		// Budget_Year
		// Budget_Month
		// Budget_Amount_Excluding_Tax
		// Budget_Amount_Including_Tax
    }

    private function getMonthValue($row)
    {
    	foreach ($row as $key => $value) {
			if(!in_array($key, ['ab', 'srt', 'type', 'value', 'per', ''])) {
				return round($value, 2);
			}
		}
		return 0;
    }
}

  // "ab" => "BUD"
  // "srt" => 101
  // "type" => "SALES"
  // "value" => "BPL"
  // "per" => 2020
  // "" => null
  // "january" => 319970.58024374
  // "february" => 298430.70214374
  // "march" => 343778.81624374
  // "april" => 325043.08104374
  // "may" => 351864.13724374
  // "june" => 326476.60004374
  // "july" => 356638.20384374
  // "august" => 353723.44684374
  // "september" => 356716.52984374
  // "october" => 353758.44684374
  // "november" => 356105.65984374
  // "december" => 337184.39584374
  // "total" => 4079690.6000249
  // "ytd" => 2675925.5676499
