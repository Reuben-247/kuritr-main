<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->text('post')->nullable();
            $table->text('title');
            $table->bigInteger('channel_id')->unsigned()->nullable();
            $table->string('post_position')->nullable(); //fornt page, side view
            $table->integer('total_comment')->nullable();
            $table->string('comments')->default('on'); // if turned off, you won't be able to comment on the post
            $table->string('agree_disagree')->default('on'); // if turned off you won't be able to agree or disgree
            $table->integer('total_agree')->nullable();
            $table->integer('total_disagree')->nullable();
            $table->integer('total_views')->nullable();
            $table->integer('total_engagements')->nullable();
            $table->string('sponsored')->default('no');
            $table->string('status')->default('not-approved'); //approved or not-approved
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('channel_id')->references('id')->on('channels')
            ->onUpdate('cascade')->onDelete('cascade');
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
