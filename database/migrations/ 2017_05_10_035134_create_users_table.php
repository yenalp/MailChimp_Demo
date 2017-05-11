<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('first_name', 120)->nullable()->index();
            $table->string('last_name', 120)->nullable()->index();
            $table->string('user_name', 512)->unique();
            $table->string('email', 512)->nullable()->index();
            $table->string('password', 100);
            $table->string('remember_token', 255)->nullable();
            $table->string('user_type')->index();
            $table->boolean('disabled')->index()->default(0);
            $table->boolean('logging_enabled')->index()->default(0);
            $table->string('token_admin', 256)->nullable();
            $table->timestamp('token_expires_admin')->nullable();
            $table->integer('no_active_users')->index()->default(0);
            $table->json('permissions')->nullable()->default('[]');
        });
    }

    public function down()
    {
        Schema::drop('users');
    }
}
