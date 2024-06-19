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
        Schema::create('site_item', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('price');
            $table->string('quantity');
            $table->enum('type',['product','service','addOn']);
            $table->string('siteId');
            $table->int('productId');            
            $table->int('total');
            $table->string('registeredBy');
            $table->string('registratorName');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_item');
    }
}
