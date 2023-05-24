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
        Schema::create('cost_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cost_id')->unsigned();
            $table->string('name',40)->index();
            $table->string('lang',2)->index();
            $table->unique(['cost_id', 'lang']);
            $table->foreign('cost_id')->references('id')->on('costs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cost_translations');
    }
};
