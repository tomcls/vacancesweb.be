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
        Schema::create('house_amenities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('house_id')->unsigned();
            $table->bigInteger('amenity_id')->unsigned();
            $table->string('value',30)->nullable();
            $table->foreign('house_id')->references('id')->on('houses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('amenity_id')->references('id')->on('amenities')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_amenities');
    }
};
