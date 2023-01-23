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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number_group')
                ->nullable()
                ->comment('This should be filled when user pays for items simultaneously');
            $table->string('invoice_number');
            $table->double('amount')->default(0);
            $table->date('payment_date');
            $table->time('payment_time');
            $table->boolean('is_annualy')->default(false);
            $table->boolean('is_monthly')->default(false);
            $table->boolean('is_weekly')->default(false);
            $table->boolean('is_daily')->default(false);
            $table->tinyInteger('annualy')->nullable();
            $table->tinyInteger('monthly')->nullable();
            $table->tinyInteger('weekly')->nullable();
            $table->tinyInteger('daily')->nullable();
            $table->tinyInteger('status')
                ->comment('1 paid, 2 pending, 3 draft');
            $table->tinyInteger('user_type')
                ->comment('1 for internal user , 2 for external user');
            $table->unsignedBigInteger('user_id');
            $table->integer('income_category_id');
            $table->integer('income_method_id')
                ->comment('payment method, related to income_methods table');
            $table->integer('institution_id')
                ->comment('Related to intitutions table')
                ->nullable();
            $table->integer('institution_class_id')
                ->nullable();
            $table->integer('institution_class_level_id')
                ->nullable();
            $table->tinyInteger('payment_at_class')
                ->nullable()
                ->comment('This field as marker for user, in which class he was paid this payment');
            $table->unsignedBigInteger('payment_target_position')
                ->comment('this column to record to which position this payment was received, related to positions table');
            $table->unsignedBigInteger('payment_target_user')
                ->comment('this column to record to whom this payment was received, related to employees table');
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
        Schema::dropIfExists('payments');
    }
};
