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
            $table->string('GL_Account_Name');
            $table->string('GL_Account_Type');
            $table->enum('Income_Balance', ['IS', 'BS']);
            $table->string('COA_Group')->nullable();
            $table->string('COA_Group_Name')->nullable();
            $table->tinyInteger('Blocked')->nullable();
            $table->string('Company_Code', 10);

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
        Schema::dropIfExists('g_l_accounts');
    }
}
