<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsRequestAcceptedColumnAndMakePostIdColumnNullableInNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('post_id')->nullable()->change();
            $table->boolean('is_request_accepted')->nullable();
        });
    }
}
