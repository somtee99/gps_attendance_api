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
            $table->string('uuid', 150)->nullable()->default(null);
            $table->string('first_name', 150)->nullable()->default(null);
            $table->string('middle_name', 150)->nullable()->default(null);
            $table->string('last_name', 150)->nullable()->default(null);
            $table->string('matric_no', 150)->unique()->nullable()->default(null);
            $table->string('user_type', 150)->nullable()->default(null);
            $table->string('email', 150)->unique()->nullable()->default(null);
            $table->string('phone', 150)->unique()->nullable()->default(null);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable()->default(null);
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
