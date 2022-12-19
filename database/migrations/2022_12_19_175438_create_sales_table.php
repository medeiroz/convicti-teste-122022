<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('roaming_branch_office_id')->nullable();
            $table->string('description');
            $table->float('price');
            $table->datetime('sold_at');

            $table->timestamps();
            $table->foreign('seller_id')->references('id')->on('users');
            $table->foreign('roaming_branch_office_id')->references('id')->on('branch_offices');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
