<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogUpdateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('catalog_update_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status', [
                'new', 'success', 'error',
            ])->default('new');
            $table->string('message')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('catalog_update_sessions');
    }
}
