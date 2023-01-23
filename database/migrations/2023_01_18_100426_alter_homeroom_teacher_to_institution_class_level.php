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
        Schema::table('institutions_class_level', function (Blueprint $table) {
            $table->after('name', function($table) {
                $table->unsignedBigInteger('homeroom_teacher')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institutions_class_level', function (Blueprint $table) {
            $table->dropColumn('homeroom_teacher');
        });
    }
};
