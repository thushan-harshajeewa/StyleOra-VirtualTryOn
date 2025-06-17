<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;
    protected $fillable=['product_id','color','profile_picture','is_display'];

    public function productItems()
    {
        return $this->hasMany(ProductItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
