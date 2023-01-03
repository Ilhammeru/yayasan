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
        Schema::create('internal_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->foreignId('institution_id')
                ->references('id')
                ->on('intitutions');
            $table->string('nis');
            $table->string('parent_data');
            $table->string('phone', 15);
            $table->string('address');
            $table->integer('district_id');
            $table->integer('city_id');
            $table->integer('province_id');
            $table->foreignId('institution_class_id')
                ->references('id')
                ->on('institution_class');
            $table->foreignId('institution_class_level_id')
                ->references('id')
                ->on('institutions_class_level');
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
        Schema::table('internal_users', function(Blueprint $table) {
            $table->dropForeign(['institution_id', 'institution_class_id', 'institution_class_level_id']);
        });
        Schema::dropIfExists('internal_users');
    }
};
