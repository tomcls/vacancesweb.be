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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname',60);
            $table->string('lastname',60);
            $table->string('lang',2);
            $table->string('phone',40)->nullable();
            $table->string('street',200)->nullable();
            $table->string('street_number',7)->nullable();
            $table->string('street_box',7)->nullable();
            $table->string('zip',10)->nullable();
            $table->string('country',100)->nullable();
            $table->boolean('active')->default(false);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
