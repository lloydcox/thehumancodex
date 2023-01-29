<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->integer('causer_id');
            $table->string('causer_type');
            $table->integer('target_id');
            $table->string('target_type');
            $table->string('action');
            $table->dropColumn('content');
            $table->dropColumn('link');
            $table->dropColumn('type');
            $table->dropColumn('content_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('causer_id');
            $table->dropColumn('causer_type');
            $table->dropColumn('target_id');
            $table->dropColumn('target_type');
            $table->dropColumn('action');
            $table->string('content');
            $table->string('link');
            $table->string('type');
        });
    }
}
