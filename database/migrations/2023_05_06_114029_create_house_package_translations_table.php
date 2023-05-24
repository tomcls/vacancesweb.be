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
        Schema::create('house_package_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('house_package_id')->unsigned();
            $table->string('name',40)->index();
            $table->string('lang',2)->index();
            $table->unique(['house_package_id', 'lang']);
            $table->foreign('house_package_id')->references('id')->on('house_packages')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_package_translations');
    }
};
