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
        Schema::create('Item Master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Item_No', 50);
            $table->text('Item_Description')->nullable();
            $table->string('Company_Code')->nullable();
            $table->string('Dimension1')->nullable();
            $table->string('Dimension2')->nullable();

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
        Schema::dropIfExists('Item Master');
        Schema::dropIfExists('Inventories');
    }
}
