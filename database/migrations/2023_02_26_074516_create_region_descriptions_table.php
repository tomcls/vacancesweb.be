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
        Schema::create('region_descriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('region_id')->unsigned();
            $table->string('lang',2)->index();
            $table->text('description');
            $table->string('type',15)->index();
            $table->unique(['region_id', 'lang', 'type']);
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('region_descriptions');
    }
};
