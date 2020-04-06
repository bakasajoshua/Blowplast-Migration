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
            $table->integer('GL_Account_Level_1')->nullable()->after('GL_Account_Name');
            $table->string('GL_Account_Level_2')->nullable()->after('GL_Account_Level_1');
            $table->string('GL_Account_Level_3')->nullable()->after('GL_Account_Level_2');

            // $table->dropColumn('COA_Group');
            // $table->dropColumn('COA_Group_Name');
            // $table->dropColumn('GL_Account_Type');

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
