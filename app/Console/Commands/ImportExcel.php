<?php

namespace App\Console\Commands;

use App\Imports\GLEntriesSheetImport;
use App\Imports\BlowplastImport;
use Illuminate\Console\Command;

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
        // $this->output->title('Starting Importing GL Entries');
        // (new GLEntriesSheetImport)->withOutput($this->output)->import(public_path('import/glentries.csv'));
        // $this->output->success('Import of GL Entries successful');

        $this->output->title('Starting Importing general data');
        (new BlowplastImport)->withOutput($this->output)->import(public_path('import/blowplast.xlsx'));
        $this->output->success('Import of general data successful');
    }
}
