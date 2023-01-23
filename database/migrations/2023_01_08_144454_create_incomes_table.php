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
        Schema::create('incomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number')->unique();
            $table->integer('user_type')->comment('1 for internal user, 2 for external user');
            $table->integer('user_id');
            $table->float('total_amount')->default(0);
            $table->integer('institution_id');
            $table->foreignId('income_type_id')
                ->references('id')
                ->on('income_types');
            $table->foreignId('income_method_id')
                ->references('id')
                ->on('income_methods');
            $table->timestamp('transaction_start_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->integer('created_by');
            $table->boolean('status')->default(false)->comment('0 for hold on institution, 1 for received by treasurer of foundation');
            $table->tinyInteger('payment_status')->default(3)->comment('1 for paid, 2 for partially paid, 3 for unpaid');
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
        Schema::table('income_types', function (Blueprint $table) {
            $table->dropForeign(['income_type_id', 'income_method_id']);
        });
        Schema::dropIfExists('incomes');
    }
};
