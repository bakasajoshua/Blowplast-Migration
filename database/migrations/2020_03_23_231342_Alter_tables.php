<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Customer Ledger Entries', function (Blueprint $table) {
            $table->renameColumn('Entry_No', 'CU_Leg_Entry_No');
        });

        Schema::table('GL Entries', function (Blueprint $table) {            
            $table->renameColumn('Entry_No', 'GL_Leg_Entry_No');            
            $table->renameColumn('Posting_Date', 'GL_Leg_Posting_Date');
            $table->renameColumn('Document_No', 'GL_LegDocument_No');
            $table->renameColumn('Document_Type', 'GL_LegDocument_Type');
        });

        Schema::table('Sales Invoice Credit Memo Headers', function (Blueprint $table) {
            $table->renameColumn('Document_No', 'SI_Document_No');
            $table->renameColumn('Posting_Date', 'SI_Posting_Date');
            $table->renameColumn('Due_Date', 'SI_Due_Date');
            $table->renameColumn('Order_Date', 'SI_Order_Date');
        });

        Schema::table('Sales Invoice Credit Memo Lines', function (Blueprint $table) {
            $table->renameColumn('Line_No', 'SI_Li_Line_No');
            $table->renameColumn('Document_No', 'SI_Li_Document_No');
            // $table->renameColumn('Type', 'SI_Li_Type');
            $table->renameColumn('Posting_Date', 'SI_Li_Posting_Date');
            $table->renameColumn('Order_Date', 'SI_Li_Order_Date');
            $table->renameColumn('Due_Date', 'SI_Li_Due_Date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
