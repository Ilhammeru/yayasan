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
        Schema::table('incomes', function (Blueprint $table) {
            $table->string('total_amount')->change();
        });
        Schema::table('income_payments', function (Blueprint $table) {
            $table->string('amount')->change();
            $table->dropForeign(['account_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->float('total_amount')->change();
        });
        Schema::table('income_payments', function (Blueprint $table) {
            $table->float('amount')->change();
            $table->foreignId('account_id')
                ->references('id')
                ->on('accounts');
        });
    }
};
