<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempUGGLSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_u_g_g_l_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('GL_Account_No')->nullable();
            $table->string('GL_Account_Name')->nullable();
            $table->string('Income_Balance')->nullable();
            $table->string('Status')->nullable();
            $table->string('Company_Code')->nullable();
            $table->string('GL_Account_Type')->nullable();
            $table->string('Chart_of_Account_Group')->nullable();
            $table->string('Chart_ofAccount_Group_Name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_u_g_g_l_s');
    }
}
