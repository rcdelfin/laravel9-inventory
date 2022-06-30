<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_attribute_id');
            $table->string('value');

            $table->foreign('product_attribute_id')
                ->references('id')
                ->on('product_attributes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variations', function (Blueprint $table) {
            $table->dropForeign(['product_attribute_id']);
            $table->dropForeign(['product_attribute_value_id']);
        });

        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->dropForeign(['product_attribute_id']);
        });
        Schema::dropIfExists('product_attribute_values');
    }
}
