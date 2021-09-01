<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipsTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tips_targets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tip_id');
            $table->foreign('tip_id')->references('id')->on('tips')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->double('price', 8, 2)->nullable();
            $table->boolean('is_achieved')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('tips_targets');
    }
}
