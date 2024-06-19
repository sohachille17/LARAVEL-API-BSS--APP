<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->string('billReference');
            $table->string('customerId');
            $table->string('customerName');
            $table->date('endingDate');
            $table->string('populationSouscription');
            $table->string('serialNumber');
            $table->string('subscriptionId');
            $table->string('serviceType');
            $table->string('siteName');
            $table->date('startingDate');
            $table->string('registeredBy');
            $table->string('registratorName');
            $table->string('billId');
            $table->enum('subscriptionType',['KAF','IWAY','BLUESTAR'])->default('KAF');
            $table->enum('paymentStatus',['unpaid','paid'])->default('unpaid');
            $table->enum('subscriptionStatus',['ongoing','finished', 'royalty','terminated'])->default('royalty');
            $table->enum('suspentionStatus',['suspended','active'])->default('active');
            $table->date('terminatedOn')->nullable();
            $table->enum('can_operate_unpaid',['true','false'])->default('false');
            $table->string('who_set_can_operate_unpaid')->nullable();;
            $table->string('nextTo')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
}
