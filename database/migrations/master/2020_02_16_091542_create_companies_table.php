<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Company_Code', 10)->unique();
            $table->string('Company_Name')->nullable();
            $table->string('Local_Currency_Code', 10)->nullable();
            $table->string('Country_Code', 10);

            // $table->foreign('Country_Code')->references('Country_Code')->on('Countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Companies');
    }
}
