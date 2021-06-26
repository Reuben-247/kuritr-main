<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('user_name')->unique();
            $table->string('email')->unique();
            $table->string('mobile_no')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('gender');
            $table->string('user_type_id')->default('user'); // user, journalist, publisher, 
            $table->text('description')->nullable();
            $table->string('avatar')->nullable();
            $table->text('referral_code')->nullable();
            $table->string('status')->default('active'); // active, suspended, banned
            $table->string('user_level_id')->default('user'); // admin, user, editor, super-admin
            $table->string('profile_verified')->default('no');
            $table->timestamp('last_login_date')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
