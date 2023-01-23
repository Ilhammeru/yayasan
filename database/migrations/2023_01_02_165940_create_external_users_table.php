<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('user_type')->comment('1 for public, 2 for goverment');
            $table->string('name', 200);
            $table->string('phone', 25);
            $table->string('address')->nullable();
            $table->integer('district_id');
            $table->integer('city_id');
            $table->integer('province_id');
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('external_users');
    }
};
