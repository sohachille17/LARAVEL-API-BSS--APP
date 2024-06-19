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
        Schema::create('big_subscription_items', function (Blueprint $table) {
            $table->id();
            $table->string('subscriptionId');
            $table->string('siteId');
            $table->string('siteName');
            $table->string('population');
            $table->string('server_id');
            // $table->enum('type',['KAF','IWAY','BLUESTAR'])->default('KAF');
            $table->string('type')->default('KAF');
            $table->string('serialNumber');

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
        Schema::dropIfExists('big_subscription_items');
    }
}
