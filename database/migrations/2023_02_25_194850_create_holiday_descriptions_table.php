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
        Schema::create('holiday_descriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('holiday_id')->unsigned();
            $table->string('lang',2)->index();
            $table->text('description');
            $table->unique(['holiday_id', 'lang']);
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
        Schema::dropIfExists('holiday_descriptions');
    }
};
