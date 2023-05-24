<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_seasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('house_id');
            $table->dateTime('startdate')->index();
            $table->dateTime('enddate')->index();
            $table->double('day_price')->nullable()->index();
            $table->double('week_price')->nullable()->index();
            $table->double('weekend_price')->nullable()->index();
            $table->tinyInteger('min_nights')->nullable()->index();
            $table->foreign('house_id')->references('id')->on('houses')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_seasons');
    }
};
