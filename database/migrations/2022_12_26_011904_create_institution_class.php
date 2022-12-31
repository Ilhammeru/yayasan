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
        Schema::create('institution_class', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('intitution_id')
                ->references('id')
                ->on('intitutions')
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
        Schema::table('institution_class', function(Blueprint $table) {
            $table->dropForeign(['intitution_id']);
        });
        Schema::dropIfExists('institution_class');
    }
};
