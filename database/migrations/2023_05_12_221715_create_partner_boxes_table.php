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
        Schema::create('partner_boxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id')->nullable()->index();
            $table->unsignedBigInteger('box_id')->nullable();//wordpress post id  or holiday_id
            $table->string('box_type',30)->nullable()->index();//article or holiday
            $table->unique(['partner_id','box_id','box_type']);
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
        Schema::dropIfExists('partner_boxes');
    }
};
