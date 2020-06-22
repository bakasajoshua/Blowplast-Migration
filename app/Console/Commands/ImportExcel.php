<?php

namespace App\Console\Commands;

use App\Imports\GLEntriesSheetImport;
use App\Imports\BlowplastImport;
use Illuminate\Console\Command;
use App\Customer;
use App\CustomerLedgerEntry;
use App\Inventory;
use App\GLAccounts;
use App\GLEntries;
use App\SalesInvoiceCreditMemoHeader;
use App\SalesInvoiceCreditMemoLine;

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
        $this->output->title('Starting on Data import ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting master data import ' . date('Y-m-d H:i:s'));
        // (new BlowplastImport)->withOutput($this->output)->import(public_path('import/blowplast.xlsx'));
        // $item = new Inventory;
        // $synch = $item->synchItems();
        // $customer = new Customer;
        // $synch = $customer->synchCustomer();
        // $this->output->success('Master data import successful ' . date('Y-m-d H:i:s'));

        // $this->output->title('Starting finance data import ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting GL Accounts data import ' . date('Y-m-d H:i:s'));
        // $gl = new GLAccounts;
        // $accounts = $gl->synchAccounts();
        // $this->output->success('GL Accounts data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting GL Entries data import ' . date('Y-m-d H:i:s'));
        $entries = $this->processGLEntries();
        // $this->output->success('GL Entries data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->success('Finance data import successful ' . date('Y-m-d H:i:s'));

        // $this->output->title('Starting sales data import ' . date('Y-m-d H:i:s'));
        // // $this->output->title('Starting Customer ledger entries data import ' . date('Y-m-d H:i:s'));
        // // $lines = $this->processCustomerLedgEntries();
        // // $this->output->success('Customer ledger entries data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting Sales invoice credit memo headers data import ' . date('Y-m-d H:i:s'));
        // $lines = $this->processSalesHeaders();
        // $this->output->success('Sales invoice credit memo headers data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting Sales invoice credit memo lines data import ' . date('Y-m-d H:i:s'));
        // $lines = $this->processSalesLines();
        // $this->output->success('Sales invoice credit memo lines data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->success('Sales data import successful ' . date('Y-m-d H:i:s'));

        // $this->output->title('Data import complete ' . date('Y-m-d H:i:s'));
    }

    private function processGLEntries()
    {
        $start_date = '2018-01-01';
        $final_date = '2018-01-20';
        while (strtotime($final_date) >= strtotime($start_date)) {
            $end_date = date('Y-m-d', strtotime('+5 days', strtotime($start_date)));
            $date_range = [
                        'SDate' => $start_date,
                        'EDate' => $end_date
                    ];
            // $gl = new GLEntries;
            // $synch = $gl->synchEntries($date_range);
            $start_date = date('Y-m-d', strtotime('+1 day', strtotime($end_date)));
            print_r($date_range);
        }
        return true;
    }

    private function processSalesHeaders()
    {
        $start_date = '2018-01-01';
        $final_date = '2020-05-15'; // 2020-05-15
        return $this->processImportData(SalesInvoiceCreditMemoHeader::class,
                                    'synchHeaders', $start_date,
                                    $final_date, 20);
    }

    private function processCustomerLedgEntries()
    {
        $start_date = '2018-01-01';
        $final_date = '2020-05-15';
        return $this->processImportData(CustomerLedgerEntry::class,
                                    'synchEntries', $start_date,
                                    $final_date, 30);
    }

    private function processSalesLines()
    {
        $start_date = '2018-01-01';
        $final_date = '2020-05-10';
        while (strtotime($final_date) >= strtotime($start_date)) {
            $end_date = date('Y-m-d', strtotime('+10 days', strtotime($start_date)));
            $date_range = [
                        'SDate' => $start_date,
                        'EDate' => $end_date
                    ];
            $lines = new SalesInvoiceCreditMemoLine;
            $synch = $lines->synchLines($date_range);
            $start_date = $end_date;
        }
        return true;
    }

    private function processImportData($model, $function, $start_date, $final_date, $incremental)
    {
        $model = new $model;
        while (strtotime($final_date) >= strtotime($start_date)) {
            $end_date = date('Y-m-d', strtotime('+'.$incremental.' days', strtotime($start_date)));
            $date_range = [
                        'SDate' => $start_date,
                        'EDate' => $end_date
                    ];           
            
            $synch = $model->$function($date_range);
            $start_date = $end_date;
        }
        return true;
    }
}
