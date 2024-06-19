<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;


class BigCustomerBillItem extends Model
{

	protected $table = 'big_customer_bill_item';
	
    protected $fillable = [
        'name',
        'price',
        'quantity',
		'type',
        'bill_id',
		'siteName',
        'total',
		'registeredBy',
        'registratorName'
    ];



}
