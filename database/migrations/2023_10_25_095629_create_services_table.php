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
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('category_name')->nullable();
            $table->float('capacity')->nullable();
            $table->string('amount');
            $table->string('code');
            $table->string('description');
            $table->string('registeredBy')->nullable();
            $table->string('service_capacity')->nullable(); //libelle
            $table->enum('type',['product','service','addOn']);

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
        Schema::dropIfExists('services');
    }
}
