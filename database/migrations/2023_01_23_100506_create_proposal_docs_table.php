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
        Schema::create('proposal_docs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')
                ->references('id')
                ->on('proposals')
                ->onDelete('CASCADE');
            $table->text('path');
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
        Schema::table('proposal_docs', function (Blueprint $table) {
            $table->dropForeign(['proposal_id']);
        });
        Schema::dropIfExists('proposal_docs');
    }
};
