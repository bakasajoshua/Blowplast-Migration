<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('vehicles', function () {
	$str = \App\Random::import();
    $this->info($str);
})->describe('Import Data from Excel to DB');

Artisan::command('salesKE', function () {
	$str = new \App\SalesInvoiceCreditMemoHeader;
	$this->info($str->synchHeadersKE());
})->describe('Import KE Sales Data');

Artisan::command('temps', function () {
	$str = new \App\SalesInvoiceCreditMemoHeader;
	$this->info($str->synchHeadersKE());
})->describe('Import Temporary Data');

Artisan::command('update:task', function(){
	// Sync the GL entries
	$model = new \App\GLEntries;
	$model->scheduledImport();

	// Sync the sales
	$model = \App\SalesInvoiceCreditMemoLine::scheduledImportData();
})->describe('Daily task updates');