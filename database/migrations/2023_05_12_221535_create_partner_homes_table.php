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
        Schema::create('partner_homes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id')->nullable()->index();
            $table->unsignedBigInteger('hero_id')->nullable();//wordpress post id  or holiday_id
            $table->string('image',150)->nullable();
            $table->string('hero_type',30)->nullable()->index();//article or holiday
            $table->string('testimonial_url')->nullable();//article or holiday
            $table->string('conference_url')->nullable();//article or holiday
            $table->string('lang',2)->default('fr')->index();//article or holiday
            $table->unique(['partner_id','hero_id','lang']);
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
        Schema::dropIfExists('partner_homes');
    }
};
