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
        Schema::create('costs', function (Blueprint $table) {
            $table->id();
            $table->string('code',35)->unique();
            $table->boolean('pay_available')->default(false);
            $table->boolean('mandatory')->default(false);
            $table->boolean('pay_to_owner')->nullable();
            $table->string('cost_unit',15)->nullable();
            $table->string('season_type',15)->nullable();
            $table->string('order',15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('costs');
    }
};
