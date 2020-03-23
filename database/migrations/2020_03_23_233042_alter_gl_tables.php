<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGlTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('GL Entries', function (Blueprint $table) {            
            $table->renameColumn('GL_Leg_Entry_No', 'GL_Entry_No');            
            $table->renameColumn('GL_Leg_Posting_Date', 'GL_Posting_Date');
            $table->renameColumn('GL_LegDocument_No', 'GL_Document_No');
            $table->renameColumn('GL_LegDocument_Type', 'GL_Document_Type');
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
