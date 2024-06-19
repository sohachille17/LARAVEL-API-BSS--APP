<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable  = [
        
        'name',
        'category_name',
        'capacity',
        'amount',
        'code',
        'description',
        'registeredBy',
        'type',
        'service_capacity'
        
    ];
}
