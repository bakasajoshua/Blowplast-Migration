<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GL_Accounts_Level_1', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Level_1_ID')->unique();
            $table->string('Level_1_Description', 100);
            $table->string('bs_is')->nullable();
            $table->string('Company_Code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GL_Accounts_Level_1');
    }
}
