<?php

namespace App\Temps;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;
use DB;

class TempKEGL extends BaseModel
{
    protected $guarded = ['id'];

    protected $connection = 'testdb';

    protected $table_prefix = 'fin.fin_gl_vw';

    public static function syncData($verbose=false, $monthly = true)
    {
    	TempKEGL::truncate();
    	// $data = self::dataSource();
        self::processData($verbose, date('Y'));
    }

    public static function syncPreviousData($verbose=false, $year=null)
    {
        TempKEGL::truncate();
        $previous_years = env('BACK_DATE_YEARS');
        if ($year == null) {            
            $current_year = (int)date('Y');
            $start_year = ($current_year - (int)$previous_years);
            for ($i=$current_year; $i > $start_year; $i--) { 
                self::processData($verbose, $i);
            }
        } else {
            self::processData($verbose, $year);
        }
        return true;        
    }

    private static function processData($verbose=false, $year)
    {
        $class = new TempKEGL;
        $current_year = (int)date('Y');
        $shortened_year = substr( $year, -2);
        $table = ((int)$year == $current_year) ? $class->table_prefix : $class->table_prefix . "_" . $shortened_year;
        
        if ($verbose)
            echo "==> Start pulling KE GL Data for the year " . $year . " " . date('Y-m-d H:i:s') . "\n";
        try {
            $data = DB::connection('oracle')->select('select * from ' . $table);
            if ($verbose) {
                echo "==> Finished pulling KE Data for the year " . $year . " " . date('Y-m-d H:i:s') . "\n";
                echo "==> Inserting Temp KE Data for the year " . $year . " " . date('Y-m-d H:i:s') . "\n";
            }
            foreach ($data as $key => $chunk) {
                TempKEGL::insert((array)$chunk);
            }
            if ($verbose)
                echo "==> Finished Inserting KE Data into the WH for the year " . $year . " " . date('Y-m-d H:i:s') . "\n";

        } catch (Exception $e) {
           var_dump($e);
           return false;
        }       
        
        return true;
    }

    public static function syncAllData()
    {
        try {
            $sql = 'select * from fin.fin_gl_vw_20';
            echo "==> Running {$sql} on the Oracle Connection\n";
            $data = collect(DB::connection('oracle')->select($sql));
            echo "==> Response: \n";
            print_r($data);
        } catch (Exception $e) {
           var_dump($e);
           return false;
        } 
    }
}


