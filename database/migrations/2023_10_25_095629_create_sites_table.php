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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('location')->nullable();
            $table->string('description')->nullable();;
            $table->string('registeredBy');
            $table->string('registratorName');
            $table->string('customerId');
            $table->enum('status',['active','inactive']);
		    $table->string('tva')->default(19.25);
			$table->string('reduction')->default(0)
            $table->enum('status',['active','inactive']);


            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
}
