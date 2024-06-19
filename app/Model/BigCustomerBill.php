<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BigCustomerBill extends Model{

    protected $table = 'big_customer_bill';

    protected $fillable = [
        'customerId',
        'customerName',
        'customerCountry',
        'customerCity',
        'telephone1',
        'telephone2',
        'email',
        'email_2',
		'registeredBy',
        'registratorName',
		'billDate',
        'billNumber',
        'reduction_in',
        'tax_in',
        'droit_daccises',
        'montant_ttc',
        'currency',
        'tvaAmount',
        'discount',
        'isPayed',
        'sub_total',
        'small_note',
        'total',


    ];
	
	    public function customers()
    {
        return $this->belongsTo(BigCustomers::class,'customerId','id');
    }
    public function bill_item(){
        return $this->hasMany(BigCustomerBillItem::class,'bill_id');
    }
	
	


}
