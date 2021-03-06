<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Customer Master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Customer_No', 50)->unique();
            $table->string('Customer_Name')->nullable();
            $table->string('Customer_Email')->nullable();
            $table->string('Company_Code', 10)->nullable();
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
        Schema::dropIfExists('Customer Master');
        Schema::dropIfExists('Customers');
    }
}
