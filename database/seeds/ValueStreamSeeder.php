<?php

use Illuminate\Database\Seeder;
use App\ValueStreams;

class ValueStreamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $valuestreams = [
        	['value_stream' => 'EDIBLE', 'Company_Code' => 'BPL'],
        	['value_stream' => 'MISC', 'Company_Code' => 'BPL'],
        	['value_stream' => 'LUBE', 'Company_Code' => 'BPL'],
        ];
        ValueStreams::truncate();
        ValueStreams::insert($valuestreams);
    }
}
