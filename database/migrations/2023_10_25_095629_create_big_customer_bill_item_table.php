<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('big_customer_bill_item', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('price');
            $table->string('quantity');
            $table->enum('type',['product','service','addOn']);
            $table->string('bill_id');
            $table->string('siteName');
            $table->int('total');
            $table->string('registeredBy')->nullable();
            $table->string('registratorName')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('big_customer_bill_item');
    }
}
