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
        Schema::create('branch_office_seller', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_office_id');
            $table->unsignedBigInteger('seller_id');

            $table->unique(['branch_office_id', 'seller_id',]);

            $table->foreign('branch_office_id')->references('id')->on('branch_offices');
            $table->foreign('seller_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_office_seller');
    }
};
