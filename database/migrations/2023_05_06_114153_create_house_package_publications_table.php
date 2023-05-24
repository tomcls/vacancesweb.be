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
        Schema::create('house_package_publications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('house_publication_id');
            $table->unsignedBigInteger('house_package_user_id');
            $table->unique(['house_publication_id', 'house_package_user_id'],'unique_house_publication');
            $table->foreign('house_publication_id')->references('id')->on('house_publications')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('house_package_user_id')->references('id')->on('house_package_users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('house_package_publications');
    }
};
