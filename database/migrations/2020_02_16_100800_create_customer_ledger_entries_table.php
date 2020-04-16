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
            $table->bigIncrements();
            $table->string('CL_Entry_No')->nullable();
            $table->string('Document_No')->nullable();
            $table->string('Customer_No')->nullable();
            $table->date('Posting_Date')->nullable();
            $table->date('Due_Date')->nullable();
            $table->string('Sell-To-Customer-No')->nullable();
            $table->string('Sell-To-Customer-Name')->nullable();
            $table->string('Bill-To-Customer-No')->nullable();
            $table->string('Bill-To-Customer-Name')->nullable();
            $table->float('Original_Amount_LCY', 12, 2)->nullable();
            $table->float('Original_Amount', 12, 2)->nullable();
            $table->string('Currency_Code')->nullable();
            $table->float('Currency_Factor', 12, 2)->nullable();
            $table->float('Remaining_Amount_LCY', 12, 2)->default(0.00);
            $table->float('Remaining_Amount', 12, 2)->default(0.00);
            $table->tinyInteger('Open')->nullable();

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
