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
        Schema::create('wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model')
                ->comment('This will be filled with model name');
            $table->integer('user_id')
                ->comment('Related to id column in stored model');
            $table->double('debit')->default(0);
            $table->double('credit')->default(0);
            $table->unsignedBigInteger('source_id')
                ->comment('Related to payments table');
            $table->string('source_text')->nullable()
                ->comment('Description from what payment this amount received');
            $table->integer('income_category_id');
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
        Schema::dropIfExists('wallets');
    }
};
