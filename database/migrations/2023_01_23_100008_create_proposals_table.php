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
        Schema::create('proposals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->date('event_date');
            $table->time('event_time');
            $table->unsignedBigInteger('pic')
                ->comment('Related to InternalUser or Employees table');
            $table->tinyInteger('pic_user_type')->comment('1 for internal, 2 for employee');
            $table->text('description');
            $table->double('budget_total')->default(0);
            $table->double('approved_budget')->default(0);
            $table->tinyInteger('status')
                ->comment('1 for approve and budget is provide, 2 for waiting approval, 3 for approved and waiting budget, 4 for reject, 5 for draft')
                ->default(2);
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
        Schema::dropIfExists('proposals');
    }
};
