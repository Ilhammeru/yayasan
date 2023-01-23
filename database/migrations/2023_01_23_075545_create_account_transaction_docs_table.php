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
        Schema::create('account_transaction_docs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('account_transaction_id')
                ->references('id')
                ->on('account_transactions')
                ->onDelete('CASCADE');
            $table->text('path')->nullable();
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
        Schema::table('account_transaction_docs', function(Blueprint $table) {
            $table->dropForeign(['account_transaction_id']);
        });
        Schema::dropIfExists('account_transaction_docs');
    }
};
