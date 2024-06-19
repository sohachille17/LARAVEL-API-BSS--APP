<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [

        'customerId',
        'payementDate',
        'siteName',
        'billReference',
        'amount',
        'paymentMethod',
        'payementAttachment1',
        'payementAttachment2',
        'transactionNumber1',
        'transactionNumber2',
        'comment',
        'registeredBy'

    ];

    public function customers()
    {
        return $this->hasOne('App\Customers');
    }

    public function user()
    {
        return $this->hasMany('App\User');
    }

}
