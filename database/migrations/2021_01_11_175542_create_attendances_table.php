<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 150)->nullable()->default(null);
            $table->string('user_uuid', 150)->nullable()->default(null);
            $table->string('lecture_uuid', 150)->nullable()->default(null);
            $table->string('longitude')->nullable()->default(null);
            $table->string('latitude')->nullable()->default(null);
            $table->string('elevation')->nullable()->default(null);
            $table->string('type', 150)->nullable()->default(null);
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
        Schema::dropIfExists('attendances');
    }
}
