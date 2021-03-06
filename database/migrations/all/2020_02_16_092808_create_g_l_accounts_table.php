<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGLAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GL Accounts', function (Blueprint $table) {
            $table->string('GL_Account_No', 50)->primary();
            $table->string('GL_Account_Name')->nullable();
            $table->enum('Income_Balance', ['IS', 'BS'])->nullable();
            $table->tinyInteger('Blocked')->nullable();
            $table->string('Company_Code', 10)->nullable();
            $table->integer('GL_Account_Level_1')->nullable();
            $table->string('GL_Account_Level_2')->nullable();
            $table->string('GL_Account_Level_3')->nullable();

            // $table->foreign('Company_Code')->references('Company_Code')->on('Companies');
            // $table->foreign('GL_Account_Level_1')->references('Level_1_ID')->on('GL_Accounts_Level_1');
            // $table->foreign('GL_Account_Level_2')->references('Level_2_ID')->on('GL_Accounts_Level_2');
            // $table->foreign('GL_Account_Level_3')->references('Level_3_ID')->on('GL_Accounts_Level_3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GL Accounts');
    }
}
