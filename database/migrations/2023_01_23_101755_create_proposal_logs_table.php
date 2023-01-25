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
        Schema::create('proposal_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('proposal_id')
                ->references('id')
                ->on('proposals')
                ->onDelete('CASCADE');
            $table->tinyInteger('status')->comment('1 for approve and budget is provide, 2 for waiting approval, 3 for approved and waiting budget, 4 for reject');
            $table->string('description');
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
        Schema::table('proposal_logs', function (Blueprint $table) {
            $table->dropForeign(['proposal_id']);
        });
        Schema::dropIfExists('proposal_logs');
    }
};
