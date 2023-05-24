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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('holiday_type_id')->unsigned();
            $table->decimal('longitude',18,15)->nullable()->index();
            $table->decimal('latitude',18,15)->nullable()->index();
            $table->tinyInteger('number_people',false)->unsigned()->nullable();
            $table->tinyInteger('stars')->nullable()->index();
            $table->boolean('active')->default(false)->index();
            $table->string('movie_url',250)->nullable();
            $table->string('web_url',250)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('holiday_type_id')->references('id')->on('holiday_types')->onUpdate('cascade');
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
        Schema::dropIfExists('holidays');
    }
};
