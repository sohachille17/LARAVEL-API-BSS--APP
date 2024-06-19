<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;


class Site extends Model
{

	protected $table = 'sites';
	
    protected $fillable = [
        'name',
        'location',
        'description',
		'registeredBy',
        'registratorName',
		'customerId',
        // 'tva',
        'status',
    ];



}
