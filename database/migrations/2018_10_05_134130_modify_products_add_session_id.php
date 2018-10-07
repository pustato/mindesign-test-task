<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProductsAddSessionId extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        \Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('session_id')
                ->nullable()
                ->default(null)
                ->after('external_id')
            ;

            $table->foreign('session_id')
                ->references('id')
                ->on('catalog_update_sessions')
                ->onDelete('set null')
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        \Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropColumn('session_id');
        });
    }
}
