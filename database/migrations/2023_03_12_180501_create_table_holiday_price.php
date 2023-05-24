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
        Schema::create('holiday_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('holiday_id')->unsigned();
            $table->dateTime('departure_date')->index();
            $table->string('departure_from',15)->index();
            $table->tinyInteger('duration_days');
            $table->tinyInteger('duration_nights');
            $table->float('price');
            $table->float('price_customer')->index();
            $table->tinyInteger('discount')->default(0);
            $table->boolean('lowest_price')->nullable()->index();
            $table->string('info',6)->nullable();
            $table->foreign('holiday_id')->references('id')->on('holidays')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('holiday_prices');
    }
};
