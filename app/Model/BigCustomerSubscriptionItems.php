<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BigCustomerSubscriptionItems extends Model
{
    protected $table = 'big_subscription_items';

    protected $fillable = [

        'subscriptionId',
        'siteId',
        'siteName',
        'population',
        'server_id',
        'type',
        'serialNumber'

    ];

    // customers linking class
    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }



}
