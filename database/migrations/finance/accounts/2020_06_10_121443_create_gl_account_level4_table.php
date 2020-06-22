<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlAccountLevel4Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GL_Accounts_Level_4', function (Blueprint $table) {
            $table->string('Level_4_ID')->primary();
            $table->string('Level_4_Description');
            $table->string('Level_3_ID');
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
        Schema::dropIfExists('GL_Accounts_Level_4');
    }
}
