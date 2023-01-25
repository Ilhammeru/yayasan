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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('homeroom_institution_id')->nullable();
            $table->integer('homeroom_class_id')->nullable();
            $table->integer('homeroom_level_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('homeroom_institution_id');
            $table->dropColumn('homeroom_class_id');
            $table->dropColumn('homeroom_level_id');
        });
    }
};
