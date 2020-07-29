<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempUGGLEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_u_g_g_l_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('Entry_No')->nullable();
            $table->string('TransType')->nullable();
            $table->string('GroupMask')->nullable();
            $table->string('GL_Account_Number')->nullable();
            $table->float('Balancing_GL_Account_No')->nullable();
            $table->float('Debit')->nullable();
            $table->float('Credit')->nullable();
            $table->float('Amount')->nullable();
            $table->string('TransCurr')->nullable();
            $table->string('Posting_Date')->nullable();
            $table->string('Document_Number')->nullable();
            $table->string('Document_Type')->nullable();
            $table->text('Description')->nullable();
            $table->string('Company_Code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_u_g_g_l_entries');
    }
}