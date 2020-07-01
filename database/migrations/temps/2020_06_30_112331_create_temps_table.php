<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temps', function (Blueprint $table) {
            $table->string("org_desc")->nullable();
            $table->string("curr_sp")->nullable();
            $table->string("curr_bs")->nullable();
            $table->string("eo_nm")->nullable();
            $table->string("inv_type")->nullable();
            $table->string("inv_type_desc")->nullable();
            $table->string("cld_id")->nullable();
            $table->string("org_id")->nullable();
            $table->string("ho_org_id")->nullable();
            $table->string("invoice_doc_id")->nullable();
            $table->string("invoice_id")->nullable();
            $table->string("invoice_doc_dt")->nullable();
            $table->string("itm_rate")->nullable();
            $table->string("itm_rate_bs")->nullable();
            $table->string("itm_id")->nullable();
            $table->string("itm_desc")->nullable();
            $table->string("itm_ship_qty")->nullable();
            $table->string("itm_ship_qty_bs")->nullable();
            $table->string("itm_amt_gs")->nullable();
            $table->string("tax_1")->nullable();
            $table->string("tax_2")->nullable();
            $table->string("tax_3")->nullable();
            $table->string("tax_4")->nullable();
            $table->string("tax_5")->nullable();
            $table->string("net_amnt")->nullable();
            $table->string("uom_sls")->nullable();
            $table->string("uom_basic")->nullable();
            $table->string("discount")->nullable();
            $table->string("mr_no")->nullable();
            $table->string("mrr_no")->nullable();
            $table->string("mr_dt")->nullable();
            $table->string("tin_no")->nullable();
            $table->string("std_weight")->nullable();
            $table->string("price_per_kg")->nullable();
            $table->string("shipmnt_id")->nullable();
            $table->string("in_amnt")->nullable();
            $table->string("itm_cost")->nullable();
            $table->string("profit")->nullable();
            $table->string("profit_percent")->nullable();
            $table->string("wh_id")->nullable();
            $table->string("wh_nm")->nullable();
            $table->string("so_id")->nullable();
            $table->string("so_dt")->nullable();
            $table->string("year")->nullable();
            $table->string("month")->nullable();
            $table->string("week")->nullable();
            $table->string("ord_qty")->nullable();
            $table->string("bal_qty")->nullable();
            $table->string("avl_stk")->nullable();
            $table->string("grplvl1")->nullable();
            $table->string("grplvl2")->nullable();
            $table->string("grplvl3")->nullable();
            $table->string("grplvl4")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temps');
    }
}