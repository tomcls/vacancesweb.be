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
        Schema::create('houses', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('house_type_id')->unsigned();

            $table->string('street',120)->nullable();
            $table->string('street_number',10)->nullable();
            $table->string('street_box',5)->nullable();
            $table->string('zip',45)->nullable();
            $table->string('city',45)->nullable();
            $table->string('phone',32)->nullable();
            $table->string('email',150)->nullable();
            $table->decimal('acreage',10,2)->nullable();

            $table->decimal('longitude',18,15)->nullable()->index();
            $table->decimal('latitude',18,15)->nullable()->index();
            $table->boolean('active')->default(false)->index();

            $table->tinyInteger('number_people',false)->unsigned()->nullable();
            $table->string('movie_url',250)->nullable();
            $table->string('web_url',250)->nullable();

            $table->string('external_id',250)->nullable()->index();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('house_type_id')->references('id')->on('house_types')->onUpdate('cascade');
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
        Schema::dropIfExists('houses');
    }
};
