<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('external_id')->unique();
            $table->enum('status', [
                'published', 'unpublished',
            ]);
            $table->string('title');
            $table->text('description');
            $table->string('image');
            $table->dateTime('first_invoice')->nullable()->default(null);
            $table->string('url');
            $table->decimal('price', 10, 2);
            $table->unsignedSmallInteger('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
