<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    // protected $table = 'subcriptions';

    protected $fillable = [

        'billId',
        'billReference',
        'customerId',
        'customerName',
        'endingDate',
        'populationSouscription',
        'serialNumber',
        'subscriptionId',
        'serviceType',
        'siteName',
        'startingDate',
        'registeredBy',
        'registratorName',
        'paymentStatus',      // [unpaid,paid]
        'paymentId',
        'subscriptionStatus', //[on going, pending, finished,  royalty, terminated]
        'terminatedOn',
        'subscriptionType',
        'status',
        'nextTo',
		"can_operate_unpaid",
        "who_set_can_operate_unpaid"

    ];

    // customers linking class
    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }



}
