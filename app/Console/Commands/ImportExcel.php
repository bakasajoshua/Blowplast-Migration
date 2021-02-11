<?php

namespace App\Console\Commands;

use App\Imports\CustomerBudgetImport;
use App\Imports\GLAccountsBudgetImport;
use App\Imports\GLEntriesSheetImport;
use App\Imports\BlowplastImport;
use App\Imports\CustomerValueStreamImport;
use App\Imports\InventoryBudgetImport;
use Illuminate\Console\Command;
use App\Customer;
use App\CustomerLedgerEntry;
use App\Inventory;
use App\InventoryBudget;
use App\GLAccounts;
use App\GLAccountsBudget;
use App\GLEntries;
use App\SalesInvoiceCreditMemoHeader;
use App\SalesInvoiceCreditMemoLine;
use App\Temps\TempUGGL;
use App\Temps\TempUGGLEntry;
use App\Temps\TempUGSalesHeader;
use App\Temps\TempUGSalesLine;
use App\Temps\TempReceivable;

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
        
        /**************************************/
        /******** Import Master Data *********/
        /**************************************/
        $this->output->title('Starting master data import ' . date('Y-m-d H:i:s'));
        // // (new BlowplastImport)->withOutput($this->output)->import(public_path('import/blowplast.xlsx'));
        // $this->output->title('Importing inventory data ' . date('Y-m-d H:i:s'));
        // Inventory::truncate();
        // $item = new Inventory;
        // $synch = $item->synchItems();
        // $this->output->success('Inventory data complete ' . date('Y-m-d H:i:s'));

        $this->output->title('Importing customer data ' . date('Y-m-d H:i:s'));
        // Customer::truncate();
        // $customer = new Customer;
        // $synch = $customer->synchCustomer();
        // (new CustomerValueStreamImport)->withOutput($this->output)->import(public_path('import/Customer Value Streams.csv'));
        (new CustomerBudgetImport)->withOutput($this->output)->import(public_path('import/customerwisebudget.xlsx'));

        $this->output->success('Customer data complete ' . date('Y-m-d H:i:s'));
        $this->output->success('Master data import successful ' . date('Y-m-d H:i:s'));

        /**************************************/
        /******** Import finance Data *********/
        /**************************************/
        // $this->output->title('Starting finance data import ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting UG GL Entries data import ' . date('Y-m-d H:i:s'));
        // $entries = $this->processGLEntries();
        // $this->output->success('UG GL Entries data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting GL Accounts data import ' . date('Y-m-d H:i:s'));
        // GLAccounts::truncate();
        // $gl = new GLAccounts;
        // $accounts = $gl->synchMasterAccounts();
        // // $accounts = $gl->synchAccounts();
        // $this->output->success('GL Accounts data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting GL Kenya data import ' . date('Y-m-d H:i:s'));
        // $alKE = GLAccounts::synchKEData();
        // $this->output->success('GL Kenya data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->success('Finance data import successful ' . date('Y-m-d H:i:s'));

        /**************************************/
        /******** Import sales Data *********/
        /**************************************/
        // $this->output->title('Starting sales data import ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting Customer ledger entries data import ' . date('Y-m-d H:i:s'));
        // $lines = $this->processCustomerLedgEntries();
        // $this->output->success('UG Customer ledger entries data import successful ' . date('Y-m-d H:i:s'));
        // $lines = $this->processKECustomerLedgEntries();
        // $this->output->success('KE Customer ledger entries data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->success('Customer ledger entries data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting UG Sales invoice credit memo headers data import ' . date('Y-m-d H:i:s'));
        // $lines = $this->processSalesHeaders();
        // $this->output->success('Sales UG invoice credit memo headers data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting Sales invoice credit memo lines data import ' . date('Y-m-d H:i:s'));
        // $lines = $this->processSalesLines();
        // $this->output->success('Sales invoice credit memo lines data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting KE Sales invoice data import ' . date('Y-m-d H:i:s'));
        // $lines = $this->processKESales();
        // $this->output->success('Sales KE invoice data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting KE Credit memos data import ' . date('Y-m-d H:i:s'));
        // $lines = TempReceivable::manuals(true);
        // $this->output->success('Sales KE Credit Memos data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->success('Sales data import successful ' . date('Y-m-d H:i:s'));

        // /**************************************/
        // /******** Import Budget Data *********/
        // /**************************************/
        // $this->output->title('Starting Inventory Budget data import ' . date('Y-m-d H:i:s'));
        // InventoryBudget::truncate();
        // (new InventoryBudgetImport)->withOutput($this->output)->import(public_path('import/newinventorybudgets.xlsx'));
        // $this->output->title('Completed importing Inventory Budget data ' . date('Y-m-d H:i:s'));

        // $this->output->title('Starting GL Account Budget data import ' . date('Y-m-d H:i:s'));
        // GLAccountsBudget::truncate();
        // (new GLAccountsBudgetImport)->withOutput($this->output)->import(public_path('import/KEGLAccountsBudget.xlsx'));
        // $this->output->title('Completed importing GL Account Budget data ' . date('Y-m-d H:i:s'));

        /**************************************/
        /******** Import Temp Data *********/
        /**************************************/
        // $this->output->title('Starting temp sales data import ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting temp UG Sales headers data import ' . date('Y-m-d H:i:s'));
        // $lines = $this->processTempUGSalesHeaders();
        // $this->output->success('Sales UG temp sales headers data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting UG temp Sales lines data import ' . date('Y-m-d H:i:s'));
        // $lines = $this->processTempUGSalesLines();
        // $this->output->success('Sales UG temp sales lines data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->title('Data import complete ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting finance data import ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting GL Accounts data import ' . date('Y-m-d H:i:s'));
        // $entries = $this->processGLAccountsTemp();
        // $this->output->success('GL Accounts data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->title('Starting UG GL Entries data import ' . date('Y-m-d H:i:s'));
        // $entries = $this->processGLEntriesTemp();
        // $this->output->success('UG GL Entries data import successful ' . date('Y-m-d H:i:s'));
        // $this->output->success('Finance data import successful ' . date('Y-m-d H:i:s'));
    }

    private function processGLEntries()
    {
        return $this->processImportData(GLEntries::class,
                                    'synchEntries', 5);
    }

    private function processGLAccountsTemp()
    {
        $model = new TempUGGL;
        return $model->synchEntries();
    }

    private function processGLEntriesTemp()
    {
        return $this->processImportData(TempUGGLEntry::class,
                                    'synchEntries', 5);
    }

    private function processSalesHeaders()
    {
        return $this->processImportData(SalesInvoiceCreditMemoHeader::class,
                                    'synchHeaders', 20, true);
    }

    private function processKESales()
    {
        $headers = new SalesInvoiceCreditMemoHeader;
        return $headers->synchHeadersKE();
    }

    private function processCustomerLedgEntries()
    {
        return $this->processImportData(CustomerLedgerEntry::class,
                                    'synchEntries', 30);
    }

    private function processKECustomerLedgEntries()
    {
        $model = new CustomerLedgerEntry;
        return $model->synchKEEntries();
    }

    private function processSalesLines()
    {
        return $this->processImportData(SalesInvoiceCreditMemoLine::class,
                                    'synchLines', 10, true);
    }

    private function processTempUGSalesHeaders()
    {
        return $this->processImportData(TempUGSalesHeader::class,
                                    'synchHeaders', 20);
    }

    private function processTempUGSalesLines()
    {
        return $this->processImportData(TempUGSalesLine::class,
                                    'synchLines', 10);
    }

    private function processImportData($model, $function, $incremental, $empty=false)
    {
        $start_date = '2018-01-01';
        $final_date = date('Y-m-d');
        // $start_date = '2020-06-01';
        // $final_date = '2020-06-30';
        if ($empty)
            $model::truncate();
        $model = new $model;
        while (strtotime($final_date) >= strtotime($start_date)) {
            $end_date = date('Y-m-d', strtotime('+'.$incremental.' days', strtotime($start_date)));
            if (strtotime($end_date) > strtotime($final_date))
                $end_date = $final_date;
            $date_range = [
                        'SDate' => $start_date,
                        'EDate' => $end_date
                    ];           
            
            $synch = $model->$function($date_range);
            $start_date = date('Y-m-d', strtotime('+1 day', strtotime($end_date)));
        }
        return true;
    }
}
