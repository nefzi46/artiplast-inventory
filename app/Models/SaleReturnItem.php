<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturnItem extends Model
{
    protected $guarded=[];

    public function saleReturn(){
        return $this->belongsTo(SaleReturn::class,'sale_return_id','id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
