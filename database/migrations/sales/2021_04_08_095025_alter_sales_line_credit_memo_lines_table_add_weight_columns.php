<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSalesLineCreditMemoLinesTableAddWeightColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Sales Invoice Credit Memo Lines', function (Blueprint $table) {
            $table->float('Line_Weight')->nullable();
            $table->float('Weight_MT', 12, 2)->nullable();
            $table->float('Item_Weight', 12, 2)->nullable();
            $table->float('Total_Line_Weight_MT')->nullable();

    //         ,SUM([Lines].Item_Weight_kg) AS 'Line Weight'
    // ,SUM(([Quantity] * [Item Master].STD_Weight)/1000) AS 'Weight'
    // ,SUM([Item Master].STD_Weight) AS 'Item Weight'
    // ,SUM(([Quantity] * [Lines].Item_Weight_kg)/1000) AS 'Total Line Weight(MT)'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Sales Invoice Credit Memo Lines', function (Blueprint $table) {
            $table->dropColumn('Line_Weight');
            $table->dropColumn('Weight_MT');
            $table->dropColumn('Item_Weight');
            $table->dropColumn('Total_Line_Weight_MT');
        });
    }
}
