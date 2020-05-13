<?php

namespace App\Console\Commands;

use App\Imports\GLEntriesSheetImport;
use App\Imports\BlowplastImport;
use Illuminate\Console\Command;
use App\Inventory;
use App\GLAccounts;
use App\GLEntries;

class ImportExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Data from Excel to DB';

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
        $this->output->title('Starting on Data import');
        $this->output->title('Starting master data import');
        (new BlowplastImport)->withOutput($this->output)->import(public_path('import/blowplast.xlsx'));
        $item = new Inventory;
        $synch = $item->synchItems();
        $this->output->success('Master data import successful');
        $this->output->title('Starting finance data import');
        $this->output->title('Starting GL Accounts data import');
        $gl = new GLAccounts;
        $accounts = $gl->synchAccounts();
        $this->output->success('GL Accounts data import successful');
        $this->output->title('Starting GL Entries data import');
        $entries = $this->processGLEntries();
        $this->output->success('GL Entries data import successful');
        $this->output->success('Finance data import successful');
        // $this->output->title('Starting Importing GL Entries');
        // (new GLEntriesSheetImport)->withOutput($this->output)->import(public_path('import/glentries.csv'));
        // $this->output->success('Import of GL Entries successful');

        // $this->output->title('Starting Importing general data');
        // (new BlowplastImport)->withOutput($this->output)->import(public_path('import/blowplast.xlsx'));
        // $this->output->success('Import of general data successful');
    }

    private function processGLEntries()
    {
        $start_date = '2018-01-01';
        while (strtotime(date('Y-m-d') > strtotime($start_date))) {
            $end_date = date('Y-m-d', strtotime('+5 days', strtotime($start_date)));
            $date_range = [
                        'SDate' => $start_date,
                        'EDate' => $end_date;
                    ];
            $gl = new GLEntries;
            $synch = $gl->synchEntries($date_range);
            $start_date = $end_date;
        }
        return true;
    }
}
