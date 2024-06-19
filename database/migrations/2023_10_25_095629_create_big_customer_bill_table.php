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
        Schema::create('big_customer_bill', function (Blueprint $table) {
            $table->id();

            $table->string('customerId');
            $table->string('customerName');
            $table->string('customerCountry');
            $table->string('billNumber');
            $table->string('small_note')->nullable();
            $table->enum('type', ['Proforma', 'Redevance']);
            $table->enum('currency', ['Fcfa', 'Euro','Dollar']);
            $table->enum('isPayed', ['0', '1']);
            $table->double('tvaAmount')->nullable();
            $table->double('droit_daccises')->default(0);
            $table->double('montant_ttc')->default(0);
            $table->double('discount')->default(0);
            $table->double('sub_total')->default(0);
            $table->double('total')->default(0);
			$table->date('billDate');
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
        Schema::dropIfExists('big_customer_bill');
    }
}
