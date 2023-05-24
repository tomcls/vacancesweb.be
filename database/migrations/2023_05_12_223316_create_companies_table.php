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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('email',150)->index();
            $table->string('phone',40)->nullable();
            $table->string('name',150)->index();
            $table->string('vat',25)->nullable();
            $table->boolean('active')->default(true)->index();
            $table->string('street',200)->nullable();
            $table->string('street_number',7)->nullable();
            $table->string('street_box',7)->nullable();
            $table->string('zip',10)->nullable();
            $table->string('country',100)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('companies');
    }
};
