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
        Schema::create('holiday_type_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('holiday_type_id')->unsigned();
            $table->string('name',40)->index();
            $table->string('slug',40)->index();
            $table->string('lang',2)->index();
            $table->unique(['holiday_type_id', 'lang']);
            $table->foreign('holiday_type_id')->references('id')->on('holiday_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('holiday_type_translations');
    }
};
