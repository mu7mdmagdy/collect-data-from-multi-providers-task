<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PaymentApp\TransactionModule\Contexts\TransactionStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedDouble('paid_amount', 15, 2)->comment('The amount of money paid by the user');
            $table->char('currency',3)->comment('The currency of the user in ISO 4217 format');
            $table->string('parent_email')->nullable()->comment('The email of the parent user');
            $table->string('status')->default(TransactionStatus::PENDING)->comment('The status of the transaction');
            $table->unsignedInteger('status_code')->default(0)->comment('The status code of the transaction');
            $table->timestamp('payment_date')->nullable()->comment('The date of the payment');
            $table->string('parent_identification')->nullable()->comment('The identification of the parent user');

            $table->timestamps();

            $table->foreign('parent_email')->references('email')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
