<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Inventories', function (Blueprint $table) {
            $table->string('Item_No', 50)->primary();
            $table->text('Item_Description');
            $table->string('Company_Code');
            $table->string('Customer_No')->nullable();
            $table->string('Dimension1')->nullable();
            $table->string('Dimension2')->nullable();

            // $table->foreign('Company_Code')->references('Company_Code')->on('Companies');
            // $table->foreign('Customer_No')->references('Customer_No')->on('Customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Inventories');
    }
}
