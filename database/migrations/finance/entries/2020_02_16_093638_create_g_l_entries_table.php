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
            $table->bigIncrements('id');
            $table->string('GL_Entry_No', 50);
            $table->string('Level_1_ID')->nullable();
            $table->string('Level_1_Description', 100)->nullable();
            $table->string('Level_2_ID')->nullable();
            $table->string('Level_2_Description')->nullable();
            $table->string('Level_3_ID')->nullable();
            $table->string('Level_3_Description')->nullable();
            $table->string('Level_4_ID')->nullable();
            $table->string('Level_4_Description')->nullable();
            $table->string('GL_Account_No', 50)->nullable();
            $table->string('GL_Account_Name')->nullable();
            $table->enum('Income_Balance', ['IS', 'BS'])->nullable();
            $table->string('Balancing_GL_Account_No', 50)->nullable();
            $table->float('Debit', 24, 2)->nullable();
            $table->float('Credit', 24, 2)->nullable();
            $table->float('Amounts', 24, 2)->nullable();
            $table->string('Currency_Code', 10)->nullable();
            $table->date('GL_Posting_Date')->nullable();
            $table->date('Day')->nullable();
            $table->string('week')->nullable();
            $table->string('month')->nullable();
            $table->tinyInteger('quarter')->nullable();
            $table->integer('year')->nullable();
            $table->string('GL_Document_No')->nullable();
            $table->string('GL_Document_Type')->comment("['Payments', 'Invoice', 'Credit Memo', 'Finance', 'Charge', 'Reminder', 'Refund']")->nullable();
            $table->text('Description')->nullable();
            $table->string('Company_Code', 10)->nullable();

            // $table->foreign('Currency_Code')->references('Currency_Code')->on('Currencies');
            // $table->foreign('GL_Account_No')->references('GL_Account_No')->on('GL Accounts');
            // $table->foreign('Company_Code')->references('Company_Code')->on('Companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GL Entries');
    }
}
