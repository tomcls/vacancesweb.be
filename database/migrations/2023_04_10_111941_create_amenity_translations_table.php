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
        Schema::create('amenity_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amenity_id')->unsigned();
            $table->string('name',40)->index();
            $table->string('lang',2)->index();
            $table->unique(['amenity_id', 'lang']);
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
        Schema::dropIfExists('amenity_translations');
    }
};
