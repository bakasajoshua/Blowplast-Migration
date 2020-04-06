<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGlaccountAddLevelColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('GL Accounts', function (Blueprint $table) {
            $table->dropColumn('COA_Group');
            $table->dropColumn('COA_Group_Name');
            $table->dropColumn('GL_Account_Type');
            
            $table->integer('GL_Account_Level_1')->nullable()->after('GL_Account_Name');
            $table->bigInteger('GL_Account_Level_2')->nullable()->after('GL_Account_Level_1');
            $table->bigInteger('GL_Account_Level_3')->nullable()->after('GL_Account_Level_2');

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
        //
    }
}
