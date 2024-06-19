<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;


class SiteItem extends Model
{

	protected $table = 'site_item';
	
    protected $fillable = [
        'name',
        'price',
        'quantity',
		'type',
        'siteId',
        'productId',
        'total',
        'type',
		'registeredBy',
        'registratorName'
    ];



}
