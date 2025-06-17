<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','product_item_id','quantity'];

    public function productItem(){
        return $this->belongsTo(ProductItem::class);
    }
}
