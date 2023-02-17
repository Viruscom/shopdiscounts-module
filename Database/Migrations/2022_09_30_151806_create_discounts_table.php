<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type_id');
            $table->string('name', 255);
            $table->integer('client_group_id');
            $table->date('valid_from')->nullable()->default(null);
            $table->date('valid_until')->nullable()->default(null);
            $table->bigInteger('max_uses')->nullable()->default(null);
            $table->bigInteger('current_uses')->default(0);
            $table->boolean('active')->default(false);
            $table->string('promo_code',255)->nullable()->default(null);
            $table->decimal('value',10,2)->nullable()->default(null);
            $table->tinyInteger('applies_to')->nullable()->default(null);
            $table->bigInteger('product_id')->unsigned()->nullable()->default(null);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('brand_id')->unsigned()->nullable()->default(null);
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
            
            $table->decimal('order_value',10,2)->nullable()->default(null);
            $table->longText('data')->nullable()->default(null);
            $table->softDeletes();

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
        Schema::dropIfExists('discounts');
    }
}
