<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->enum('customProfile', ['bigAccount','simpleAccount'])->default('simpleAccount');
            $table->string('username');
            $table->string('name');
            $table->char('generatedCustomId',250);
            $table->string('country');
            $table->string('city');
            $table->string('telephone1');
            $table->string('telephone2');
            $table->char('email',200)->nullable();
            $table->string('email_2')->nullable();
            $table->enum('type', ['residential','business'])->default('residential');
            $table->integer('status');
            $table->integer('active');
            $table->integer('deleted');
            $table->string('region');
            $table->string('register')->nullable();
            //$table->string('taxPayerNumber')->nullable();

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
        Schema::dropIfExists('customers');
    }
}
