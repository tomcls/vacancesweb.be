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
        Schema::create('partner_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id')->index();
            $table->unsignedBigInteger('post_id')->index();
            $table->string('lang',2)->default('fr')->index();//article or holiday
            $table->unique(['partner_id','post_id','lang']);
            $table->tinyInteger('sort')->nullable()->index();
            $table->foreign('partner_id')->references('id')->on('partners')->onUpdate('cascade')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner_articles');
    }
};
