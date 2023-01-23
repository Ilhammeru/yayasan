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
        Schema::create('income_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('income_id')
                ->references('id')
                ->on('incomes');
            $table->float('amount')->default(0);
            $table->foreignId('account_id')
                ->references('id')
                ->on('accounts');
            $table->text('proof_payment')->nullable();
            $table->timestamp('payment_time')->nullable();
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
        Schema::table('income_items', function (Blueprint $table) {
            $table->dropForeign(['income_id', 'account_id']);
        });
        Schema::dropIfExists('income_payments');
    }
};
