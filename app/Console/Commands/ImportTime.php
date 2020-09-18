<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Day;
use App\Month;
use App\MonthOfYear;
use App\Quarter;
use App\Week;
use App\Year;

class ImportTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import time data.';

    /**
    * User defined class attributes
    */
    private $quarters = [
            'Q1' => [1,2,3],
            'Q2' => [4,5,6],
            'Q3' => [7,8,9],
            'Q4' => [10,11,12],
        ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->import_time();
    }

    private function import_time()
    {
        // Truncate the time data
        Year::truncate();
        Quarter::truncate();
        MonthOfYear::truncate();
        Month::truncate();
        Week::truncate();
        Day::truncate();
        // dd('truncate');
        // Truncate the time data

        // Putting in the months of the year
        $this->createQuarterData();
        $this->createMonthOfYearData();
        // Putting in the months of the year

        $current_year = NULL;
        $current_year_data = NULL;
        $current_month = NULL;
        $current_month_data = NULL;
        $current_week = NULL;
        $current_week_data = NULL;
        $days = collect([]);
        $loop_date = env('START_DATE');
        while (strtotime($loop_date) < strtotime(env('END_DATE'))) {
            // Get and create the year
            if (!isset($current_year)) {
                $current_year = date('Y', strtotime($loop_date));
                $current_year_data = $this->createYearData($current_year);
            } else {
                if ($current_year != date('Y', strtotime($loop_date))) {
                    $current_year = date('Y', strtotime($loop_date));
                    $current_year_data = $this->createYearData($current_year);
                }
            }
            // Get and create the year

            // Get and create the month
            if (!isset($current_month)) {
                $current_month = date('Y/m', strtotime($loop_date));
                $current_month_data = $this->createMonthData($current_year, date('m', strtotime($loop_date)));
            } else {
                if ($current_month != date('Y/m', strtotime($loop_date))) {
                    $current_month = date('Y/m', strtotime($loop_date));
                    $current_month_data = $this->createMonthData($current_year, date('m', strtotime($loop_date)));
                }
            }
            // Get and create the month

            // Create weeks
            $current_week = $this->getCurrentWeek($loop_date);
            
            if (Week::where('week', $current_week['week'])->get()->isEmpty()) {
                $this->createWeekData($current_week);
            }
            // Create weeks

            // Create the day
            $days->push([
                            'day_id' => $loop_date,
                            'week' => $current_week['week'],
                            'month' => $current_month
                        ]);
            $loop_date = date('Y-m-d', strtotime("+1 day", strtotime($loop_date)));
        }

        // Insert the dates
        $chunks = $days->chunk(100);
        foreach ($chunks as $key => $chunk) {
            Day::insert($chunk->toArray());
        }
    }

    private function createYearData($year)
    {
        $length = 365;
        if (($year % 4) == 0)
            $length = 366;

        return Year::create([
            'year' => $year,
            'year_date' => $year . '-01-01',
            'duration' => $length,
            'prev_year_id' => $year - 1,
        ]);
    }

    private function createQuarterData()
    {
        $quarter_of_the_year = [
            ['quarter' => 1, 'quarter_description' => 'Q1'],
            ['quarter' => 2, 'quarter_description' => 'Q2'],
            ['quarter' => 3, 'quarter_description' => 'Q3'],
            ['quarter' => 4, 'quarter_description' => 'Q4'],
        ];
        foreach ($quarter_of_the_year as $key => $quarter) {
            Quarter::create($quarter);
        }
    }

    private function createMonthOfYearData()
    {
        $month_of_year = [
            1 => 'January', 2 => 'February',
            3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June',
            7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October',
            11 => 'November', 12 => 'December',
        ];
        foreach ($month_of_year as $key => $month) {
            MonthOfYear::create(['month_of_year_id' => $key,    
                                'month_description' => $month]);
        }

        return true;
    }

    private function createMonthData($year, $month)
    {
        return Month::create([
            'month_id' => $year . '/' . $month,
            'month_of_year_id' => $month,
            'year' => $year,
            'quarter_id' => $this->getQuarter($month)->quarter,
            'num_of_days' => cal_days_in_month(CAL_GREGORIAN,$month,$year),
        ]);
    }

    private function getQuarter($month)
    {
        foreach ($this->quarters as $key => $quarter) {
            if (in_array($month, $quarter))
                return Quarter::where('quarter_description', $key)->first();
        }
    }

    private function createWeekData($week)
    {
        return Week::create($week);
    }

    private function getCurrentWeek($date)
    {
        $date = strtotime($date);
        $ret['year'] = date('Y', $date);
        $week = date('W', $date);

        $dto = new \DateTime();
        $dto->setISODate($ret['year'], $week);
        $ret['start'] = date('Y-m-d', strtotime("-1 day", strtotime($dto->format('Y-m-d'))));
        $ret['end'] = date('Y-m-d', strtotime("+6 day", strtotime($ret['start'])));
        $ret['last_week'] = date('o/W', strtotime($ret['start']));
        $ret['next_week'] = date('o/W', strtotime("+2 days", strtotime($ret['end'])));
        $ret['week'] = date('o/W', strtotime($ret['end']));
        return $ret;
    }
}
