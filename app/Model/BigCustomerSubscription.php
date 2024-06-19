<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BigCustomerSubscription extends Model
{
    protected $table = 'big_customers_subscriptions';

    protected $fillable = [

        'billId',
        'billReference',
        'customerId',
        'customerName',
        'endingDate',
        // 'populationSouscription',
        // 'serialNumber',
        // 'subscriptionId',
        // 'serviceType',
        // 'siteName',
        'startingDate',
        'registeredBy',
        'registratorName',
        'paymentStatus',      // [unpaid,paid]
        'paymentId',
        'subscriptionStatus', //[on going, pending, finished,  royalty, terminated]
        'terminatedOn',
        // 'subscriptionType',
        'status',
        'nextTo',
		"can_operate_unpaid",
        "who_set_can_operate_unpaid"

    ];

    // [
        // useless feilds
    //     populationSouscription,
    //     serialNumber,
    //     subscriptionId,
    //     serviceType,
    //     siteName,
    //     subscriptionType,

    // ]

    // customers linking class
    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }



}
