<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    use HasFactory;
    
    protected $fillable = ['product_color_id', 'size', 'price', 'stock'];

    public function productColor(){
        return $this->belongsTo(ProductColor::class);
    }
}
