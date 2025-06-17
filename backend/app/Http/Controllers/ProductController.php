<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProductList(Request $request)
{
    $query = Product::with([
        'brand:id,name,brand_image',
        'category:id,name',
        'productColors' => function ($q) {
            $q->select('id', 'product_id', 'color', 'product_picture', 'is_display')
              ->where('is_display', true); // Include only colors where is_display is true
        },
        'productColors.productItems' => function ($q) {
            $q->select('id', 'product_color_id', 'size', 'price')
              ->where('size', 'S'); // Include only items where size is 'S'
        }
    ])->select('id', 'name', 'brand_id', 'category_id', 'person_type', 'main_image');

    // Apply filters from request
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->filled('brand_id')) {
        $query->where('brand_id', $request->brand_id);
    }

    if ($request->filled('person_type')) {
        $query->where('person_type', $request->person_type);
    }

    if ($request->filled('color')) {
        $query->whereHas('productColors', function ($q) use ($request) {
            $q->where('color', $request->color);
        });
    }

    if ($request->filled('size')) {
        $query->whereHas('productColors.productItems', function ($q) use ($request) {
            $q->where('size', $request->size);
        });
    }

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    

    // Retrieve products
    $products = $query->get()->map(function ($product) {
        $displayColor = $product->productColors->first(); // Get the first color where is_display=true
        return [
            'product_id' => $product->id,
            'name' => $product->name,
            'brand' => $product->brand->name ?? null,
            'brand_image'=>$product->brand->brand_image??null,
            'category' => $product->category->name ?? null,
            'person_type' => $product->person_type,
            'main_image' => $product->main_image,
            'product_picture' => $displayColor ? $displayColor->product_picture : null,
            'price' => $displayColor && $displayColor->productItems->first()
                ? $displayColor->productItems->first()->price
                : null,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $products,
    ]);
}



public function getProduct($id)
{
    $product = Product::with([
        'brand:id,name,brand_image',
        'category:id,name',
        'productColors' => function ($query) {
            $query->select('id', 'product_id', 'color','product_picture','is_display')
                ->with(['productItems' => function ($subQuery) {
                    $subQuery->select('id', 'product_color_id', 'size', 'price','stock');
                }]);
        }
    ])->find($id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $product,
    ]);
}

    public function getBrandList()  {
        
        $brand=Brand::all();
        return response()->json([
            'success' => true,
            'data' => $brand,
        ]);

    }

    public function getCategoryList()  {
        
        $category=Category::all();
        return response()->json([
            'success' => true,
            'data' => $category,
        ]);

    }


}
