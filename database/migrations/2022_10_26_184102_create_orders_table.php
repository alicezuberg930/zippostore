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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->dateTime('order_date');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->index('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->string('fullname', 100);
            $table->string('email', 100);
            $table->string('phone_number', 10);
            $table->text('address');
            $table->integer('quantity');
            $table->bigInteger('total_price');
            $table->smallInteger('status')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
