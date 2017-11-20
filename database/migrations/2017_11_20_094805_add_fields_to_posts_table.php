<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->text('body')->after('title');
            $table->string('slug')->unique()->after('body');
            $table->integer('category_id')->unsigned()->after('slug');
            $table->integer('user_id')->unsigned()->after('category_id');
            $table->string('image')->nullable()->after('user_id');
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
            $table->dropColumn(['title', 'body', 'slug', 'category_id', 'user_id', 'image']);
        });
    }
}
