<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductItems extends Model
{
    public $table = 'invoice_item';
    
    
    protected $fillable = 
    [
      'bills_id',
      'product_id',
      'product_name',
      'unit_price',
      'quantity',
      'total'
    ];


    public function products()
    {
      return $this->belongsTo(Service::class);
    }

    public function bills(){
        return $this->hasMany(Bills::class);
    }
    public function invoice_item(){
        return $this->hasMany(Service::class);
    }

   
}
