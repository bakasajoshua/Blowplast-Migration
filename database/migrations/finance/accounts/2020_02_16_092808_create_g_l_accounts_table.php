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
            $table->bigIncrements('id');
            $table->string('Level_1_ID')->nullable();
            $table->string('Level_1_Description', 100)->nullable();
            $table->string('Level_2_ID')->nullable();
            $table->string('Level_2_Description')->nullable();
            $table->string('Level_3_ID')->nullable();
            $table->string('Level_3_Description')->nullable();
            $table->string('Level_4_ID')->nullable();
            $table->string('Level_4_Description')->nullable();
            $table->string('GL_Account_No', 50)->unique();
            $table->string('GL_Account_Name')->nullable();
            $table->enum('Income_Balance', ['IS', 'BS'])->nullable();
            $table->tinyInteger('Blocked')->nullable();
            $table->string('Company_Code', 10)->nullable();
            $table->string('GL_Account_Level_1')->nullable();
            $table->string('GL_Account_Level_2')->nullable();
            $table->string('GL_Account_Level_3')->nullable();
            $table->string('GL_Account_Level_4')->nullable();
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
