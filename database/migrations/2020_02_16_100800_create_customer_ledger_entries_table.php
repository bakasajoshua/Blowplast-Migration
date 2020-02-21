<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerLedgerEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Customer Ledger Entries', function (Blueprint $table) {
            $table->string('Entry_No', 10)->primary();
            $table->string('Document_No');
            $table->string('Customer_No');
            $table->date('Posting_Date');
            $table->date('Due_Date')->nullable();
            $table->string('Sell-To-Customer-No');
            $table->string('Sell-To-Customer-Name')->nullable();
            $table->string('Bill-To-Customer-No');
            $table->string('Bill-To-Customer-Name')->nullable();
            $table->decimal('Original_Amount_LCY');
            $table->decimal('Original_Amount');
            $table->string('Currency_Code');
            $table->decimal('Currency_Factor');
            $table->decimal('Remaining_Amount_LCY')->default(0.00);
            $table->decimal('Remaining_Amount')->default(0.00);
            $table->tinyInteger('Open');

            // $table->foreign('Customer_No')->references('Customer_No')->on('Customer Master');
            // $table->foreign('Currency_Code')->references('Currency_Code')->on('Currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Customer Ledger Entries');
    }
}
