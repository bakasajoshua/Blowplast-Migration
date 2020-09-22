<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BaseModel;
use App\Temps\Temp;
use App\Temps\TempKEGL;
use App\Temps\TempPayables;
use App\Temps\TempReceivable;
use App\Temps\TempUGGL;
use App\Temps\TempUGGLEntry;
use App\Temps\TempUGSaleslHeader;
use App\Temps\TempUGSalesLine;

class ImportTemp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:temps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all the temp data';

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
        $this->output->title('Starting all temp data import ' . date('Y-m-d H:i:s'));
        $this->output->title('Starting KE sales temp data import ' . date('Y-m-d H:i:s'));
        Temp::pullData(true);
        $this->output->success('Completed KE sales temp data import ' . date('Y-m-d H:i:s'));
        $this->output->title('Starting KE GL temp data import ' . date('Y-m-d H:i:s'));
        TempKEGL::syncData(true);
        $this->output->success('Completed KE GL temp data import ' . date('Y-m-d H:i:s'));
        $this->output->title('Starting KE Payables temp data import ' . date('Y-m-d H:i:s'));
        TempPayables::insertData(true);
        $this->output->success('Completed KE Payables temp data import ' . date('Y-m-d H:i:s'));
        $this->output->title('Starting KE Receivables temp data import ' . date('Y-m-d H:i:s'));
        TempReceivable::insertData(true);
        $this->output->success('Completed KE Receivables temp data import ' . date('Y-m-d H:i:s'));
        $this->output->title('Starting UG GL Accounts temp data import ' . date('Y-m-d H:i:s'));
        TempUGGL::fillAllData();
        $this->output->success('Completed UG GL Accounts temp data import ' . date('Y-m-d H:i:s'));
        $this->output->title('Starting UG GL Entries temp data import ' . date('Y-m-d H:i:s'));
        TempUGGLEntry::fillAllData();
        $this->output->success('Completed UG GL Entries temp data import ' . date('Y-m-d H:i:s'));
        $this->output->title('Starting UG Sales Headers temp data import ' . date('Y-m-d H:i:s'));
        TempUGSaleslHeader::fillAllData();
        $this->output->success('Completed UG Sales Headers temp data import ' . date('Y-m-d H:i:s'));
        $this->output->title('Starting UG Sales Lines temp data import ' . date('Y-m-d H:i:s'));
        TempUGSalesLine::fillAllData();
        $this->output->success('Completed UG Sales Lines temp data import ' . date('Y-m-d H:i:s'));
        $this->output->success('Completed importing temp data ' . date('Y-m-d H:i:s'));
    }
}
