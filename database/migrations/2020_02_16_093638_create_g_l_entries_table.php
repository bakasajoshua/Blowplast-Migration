<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGLEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GL Entries', function (Blueprint $table) {
            $table->string('Entry_No', 50)->primary();
            $table->string('GL_Account_No', 50);
            $table->string('Balancing_GL_Account_No', 50);
            $table->decimal('Amounts');
            $table->string('Currency_Code', 10);
            $table->date('Posting_Date');
            $table->string('Document_No');
            $table->enum('Document_Type', ['Payments', 'Invoice', 'Credit Memo', 'Finance', 'Charge', 'Reminder', 'Refund']);
            $table->text('Description')->nullable();
            $table->string('Company_Code', 10);

            $table->foreign('Currency_Code')->references('Currency_Code')->on('Currencies');
            $table->foreign('GL_Account_No')->references('GL_Account_No')->on('GL Accounts');
            $table->foreign('Company_Code')->references('Company_Code')->on('Companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('g_l_entries');
    }
}
