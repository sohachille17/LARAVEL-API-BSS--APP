<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('big_payments', function (Blueprint $table) {
            $table->id();

            $table->string('customerId');
            $table->date('payementDate');
            $table->string('siteName');
            $table->string('billReference');
            $table->string('paymentMethod');
            $table->string('payementAttachment1');
            $table->string('payementAttachment2')->nullable();
            $table->string('transactionNumber1');
            $table->string('transactionNumber2')->nullable();
            $table->string('comment')->nullable();
            $table->string('registeredBy');
            $table->decimal('amount',16,3);
            

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
        Schema::dropIfExists('big_payments');
    }
}
