<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->string('tenant_id')->nullable()->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->mediumText('short_description')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active');
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('product_categories')
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
            $table->dropForeign('product_variations_product_id_foreign');
        });

        Schema::dropIfExists('products');
    }
}
