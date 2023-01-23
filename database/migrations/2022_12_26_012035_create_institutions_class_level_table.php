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
        Schema::create('institutions_class_level', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('institution_class_id')
                ->references('id')
                ->on('institution_class')
                ->onDelete('CASCADE');
            $table->string('name', 30);
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
        Schema::table('institutions_class_level', function (Blueprint $table) {
            $table->dropForeign(['institution_class_id']);
        });
        Schema::dropIfExists('institutions_class_level');
    }
};
