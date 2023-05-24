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
        Schema::create('house_document_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('house_document_id');
            $table->string('name')->index();
            $table->string('lang',2)->index();
            $table->unique(['house_document_id', 'lang']);
            $table->foreign('house_document_id')->references('id')->on('house_documents')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_document_translations');
    }
};
