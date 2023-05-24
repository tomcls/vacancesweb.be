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
        Schema::create('house_titles', function (Blueprint $table) { 
            $table->id();
            $table->bigInteger('house_id')->unsigned();
            $table->string('name')->index();
            $table->string('slug')->index();
            $table->string('lang',2)->index();
            $table->unique(['house_id', 'lang']);
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
        Schema::dropIfExists('house_titles');
    }
};
