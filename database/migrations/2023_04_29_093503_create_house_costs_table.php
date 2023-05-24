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
        Schema::create('house_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('house_id');
            $table->unsignedBigInteger('cost_id');
            $table->decimal('price',10,2);
            $table->string('cost_unit',15)->nullable();
            $table->unique(['house_id', 'cost_id']);
            $table->foreign('cost_id')->references('id')->on('costs')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('house_costs');
    }
};
