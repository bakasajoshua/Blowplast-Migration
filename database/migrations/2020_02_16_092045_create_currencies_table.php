<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Currencies', function (Blueprint $table) {
            $table->string('Currency_Code', 10)->primary();
            $table->string('Country_Code', 10);
            $table->decimal('Exchange_Rate');

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
        Schema::dropIfExists('currencies');
    }
}
