<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMfApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mf_apis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tip_id');
            $table->foreign('tip_id')->references('id')->on('tips')->onDelete('cascade');
            $table->string('scheme_code')->nullable();
            $table->string('mutual_date')->nullable();
            $table->date('mutual_nav_date')->nullable();
            $table->string('net_asset_value')->nullable();
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
        Schema::dropIfExists('mf_apis');
    }
}
