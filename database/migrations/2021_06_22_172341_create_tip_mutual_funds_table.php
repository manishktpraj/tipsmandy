<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipMutualFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tip_mutual_funds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tip_id');
            $table->foreign('tip_id')->references('id')->on('tips')->onDelete('cascade');
            $table->string('caps_type')->nullable();
            $table->string('purpose')->nullable();
            $table->string('scheme_code')->nullable();
            $table->string('isin_div_payout_isin_growth')->nullable();
            $table->string('isin_div_reinvestment')->nullable();
            $table->string('scheme_name')->nullable();
            $table->string('net_asset_value')->nullable();
            $table->string('mutual_date')->nullable();
            $table->date('mutual_nav_date')->nullable();
            $table->string('scheme_type')->nullable();
            $table->string('scheme_category')->nullable();
            $table->string('mutual_fund_family')->nullable();
            $table->json('mf_api')->nullable();
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
        Schema::dropIfExists('tip_mutual_funds');
    }
}
