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
        Schema::table('proposals', function (Blueprint $table) {
            $table->integer('approved_by')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->integer('funding_by')->nullable();
            $table->timestamp('approve_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('funding_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn('approved_by');
            $table->dropColumn('rejected_by');
            $table->dropColumn('funding_by');
            $table->dropColumn('approve_at');
            $table->dropColumn('rejected_at');
            $table->dropColumn('funding_at');
        });
    }
};
