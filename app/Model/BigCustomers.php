<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BigCustomers extends Model
{

protected $table = 'big_customers';
    protected $fillables = [

        'customProfile',
        'username',
        'name',
        'generatedCustomId',
        'country',
        'city',
        'status',
        'telephone1',
        'telephone2',
        'email',
        'email_2',
        'active',
        'deleted',
        'type',
        'region',
        'register',
        //'taxPayerNumber'


    ];


    public function bills(){
        return $this->hasMany(Bills::class, 'customer_id', 'id');

    }
}
