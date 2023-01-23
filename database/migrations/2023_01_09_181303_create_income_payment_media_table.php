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
        Schema::create('income_payment_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('income_payment_id')
                ->references('id')
                ->on('income_payments')
                ->onDelete('CASCADE');
            $table->string('path');
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
        Schema::table('income_payment_media', function (Blueprint $table) {
            $table->dropForeign(['income_payment_id']);
        });
        Schema::dropIfExists('income_payment_media');
    }
};
