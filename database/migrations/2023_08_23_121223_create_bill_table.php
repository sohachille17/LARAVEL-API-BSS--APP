<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            $table->string('customerName');
            $table->string('dateLimit')->nullable();
            $table->string('billNumber')->nullable();
            $table->string('compantName')->nullable();
            $table->string('serviceAddress')->nullable();
            $table->string('postalAddress')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('customerEmailAddress')->nullable();
            $table->string('websiteLink')->nullable();
            $table->string('small_note')->nullable();
            $table->enum('type', ['Proforma', 'Redevance']);
            $table->enum('currency', ['Fcfa', 'Euro','Dollar']);
            $table->double('tvaAmount')->nullable();
            $table->double('droit_daccises')->default(0);
            $table->double('montant_ttc')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->double('discount')->default(0);
            $table->double('sub_total')->default(0);
            $table->double('reduction_in')->default(0);
            $table->double('tax_in')->default(0);
            $table->double('total')->default(0);
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
        Schema::dropIfExists('bill');
    }
}
