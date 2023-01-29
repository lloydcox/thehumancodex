<?php

use App\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Post::all() as $post) {
            try {
                $newDate = Carbon::createFromFormat('d.m.Y.', $post->date);
            } catch (\Exception $e) {
                $newDate = Carbon::today();
            }
            $post->update(['date' => $newDate]);
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->datetime('date')->change();
            $table->string('image')->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('date')->change();
            $table->dropColumn('image');
        });
    }
}
