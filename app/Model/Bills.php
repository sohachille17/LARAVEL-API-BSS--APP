<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    //all bills model
    protected $fillable  = [


        'customer_id',
        'customerName',
        'dateLimit',
        'billNumber',
        'compantName',
        'reduction_in',
        'tax_in',
        'serviceAddress',
        'postalAddress',
        'phoneNumber',
        'customerEmailAddress',
        'websiteLink',
        'type',
        'droit_daccises',
        'montant_ttc',
        'currency',
        'tvaAmount',
        'discount',
        'status',
        'sub_total',
        'small_note',
        'total',





    ];

    public function customers()
    {
        return $this->belongsTo(Customers::class);
    }
    public function invoice_item(){
        return $this->hasMany(ProductItems::class);
    }
    public function subscription(){
        return $this->hasMany(Subscription::class,'billId', 'id');
    }



}
