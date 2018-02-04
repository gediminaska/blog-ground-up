<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('body');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->integer('category_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('image')->nullable();
            $table->integer('status')->default(1);
            $table->integer('type')->unsigned()->default(1);
            $table->dateTime('published_at');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('posts');
    }
}
