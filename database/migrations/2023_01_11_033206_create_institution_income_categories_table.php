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
        Schema::create('institution_income_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('institution_id')
                ->nullable()
                ->references('id')
                ->on('intitutions');
            $table->foreignId('income_category_id')
                ->nullable()
                ->references('id')
                ->on('income_categories');
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
        Schema::table('institution_income_categories', function(Blueprint $table) {
            $table->dropForeign(['institution_id', 'income_category_id']);
        });
        Schema::dropIfExists('institution_income_categories');
    }
};
