<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('external_id')->unique();
            $table->unsignedInteger('product_id');
            $table->enum('status', [
                'published', 'unpublished',
            ]);
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('amount');
            $table->unsignedInteger('sales');
            $table->string('article');
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
