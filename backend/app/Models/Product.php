<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;
    
    protected $fillable = ['name', 'brand_id', 'person_type', 'category_id', 'description', 'main_image'];

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function productColors()
    {
        return $this->hasMany(ProductColor::class);
    }




   
}
