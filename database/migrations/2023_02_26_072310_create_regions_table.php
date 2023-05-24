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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('country_id')->unsigned();
            $table->string('level',10)->index();
            $table->decimal('longitude',18,15)->index();
            $table->decimal('latitude',18,15)->index();
            $table->decimal('sw_lat',18,15)->index();
            $table->decimal('sw_lon',18,15)->index();
            $table->decimal('ne_lat',18,15)->index();
            $table->decimal('ne_lon',18,15)->index();
            $table->geometry('geometry')->spatialIndex();
            $table->boolean('active')->index();
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regions');
    }
};
