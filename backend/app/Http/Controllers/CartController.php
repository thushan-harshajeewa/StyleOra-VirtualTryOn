<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;


class CartController extends Controller
{

    public static function getCartDetails($userId){
        $cart = Cart::with([
            'cartItems.productItem.productColor.product.category',
            'cartItems.productItem.productColor.product.brand',
        ])->where('user_id', $userId)->first();
    
        if (!$cart) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Cart is empty',
            ]);
        }
    
        // Calculate total prices
        $cartTotalPrice = 0;
    
        $cartItems = $cart->cartItems->map(function ($item) use (&$cartTotalPrice) {
            $itemPrice = $item->productItem->price * $item->quantity; // price * quantity
            $cartTotalPrice += $itemPrice; // Add to total cart price
    
            return [
                'id' => $item->id,
                'product_item_id' => $item->product_item_id,
                'quantity' => $item->quantity,
                'item_price' => $itemPrice, // Price for this item
                'product_item' => [
                    'id' => $item->productItem->id,
                    'size' => $item->productItem->size,
                    'stock' => $item->productItem->stock,
                    'product_color' => [
                        'id' => $item->productItem->productColor->id,
                        'color' => $item->productItem->productColor->color,
                        'product_picture' => $item->productItem->productColor->product_picture,
                        'product' => [
                            'id' => $item->productItem->productColor->product->id,
                            'name' => $item->productItem->productColor->product->name,
                            'main_image' => $item->productItem->productColor->product->main_image,
                            'category' => [
                                'id' => $item->productItem->productColor->product->category->id,
                                'name' => $item->productItem->productColor->product->category->name,
                            ],
                            'brand' => [
                                'id' => $item->productItem->productColor->product->brand->id,
                                'name' => $item->productItem->productColor->product->brand->name,
                            ],
                        ],
                    ],
                ],
            ];
        });
    
        return response()->json([
            'success' => true,
            'data' => [
                'cart_id' => $cart->id,
                'user_id' => $cart->user_id,
                'cart_items' => $cartItems,
                'total_price' => $cartTotalPrice, // Overall total price
            ],
        ]);
    }

    public function getCart()
    {
        $userId = auth()->id();
    
        // Fetch the cart with its items and relationships
        return self::getCartDetails($userId);
    }
    

    /**
     * Add a product to the cart.
     */
    public function addToCart(Request $request)
    {
        $userId = auth()->id(); 
        $request->validate([
            
            'product_item_id' => 'required|exists:product_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_item_id', $request->product_item_id)
            ->first();

        if ($cartItem) {
            // Update quantity if the product already exists in the cart
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Add new item to the cart
            CartItem::create([
                'cart_id' => $cart->id,
                'product_item_id' => $request->product_item_id,
                'quantity' => $request->quantity,
            ]);
        }

        return self::getCartDetails($userId);
    }

    /**
     * Remove a product from the cart.
     */
    public function removeFromCart(Request $request)
    {
        $userId = auth()->id(); 
        // $request->validate([
            
        //     'product_item_id' => 'required|exists:product_items,id',
        // ]);

        // $cart = Cart::where('user_id', $userId)->first();

        // if (!$cart) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Cart not found',
        //     ], 404);
        // }

        $cartItem = CartItem::where('id', $request->cart_item_id)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart',
            ], 404);
        }

        $cartItem->delete();

        return self::getCartDetails($userId);
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function updateQuantity(Request $request)
    {
         $userId = auth()->id(); 
        // $request->validate([
            
        //     'product_item_id' => 'required|exists:product_items,id',
        //     'quantity' => 'required|integer|min:1',
        // ]);

        // $cart = Cart::where('user_id', $userId)->first();

        // if (!$cart) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Cart not found',
        //     ], 404);
        // }

        $cartItem = CartItem::where('id', $request->cart_item_id)->first();
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart',
            ], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return self::getCartDetails($userId);
}

}








// $cart = Cart::with([
//     'cartItems.productItem.productColor.product.category',
//     'cartItems.productItem.productColor.product.brand',
// ])->where('user_id', $userId)->first();

// // Remove timestamps from the response
// $cart->makeHidden(['created_at', 'updated_at']);
// $cart->cartItems->each(function ($item) {
//     $item->makeHidden(['created_at', 'updated_at']);
//     $item->productItem->makeHidden(['created_at', 'updated_at']);
//     $item->productItem->productColor->makeHidden(['created_at', 'updated_at']);
//     $item->productItem->productColor->product->makeHidden(['created_at', 'updated_at']);
//     $item->productItem->productColor->product->category->makeHidden(['created_at', 'updated_at']);
//     $item->productItem->productColor->product->brand->makeHidden(['created_at', 'updated_at']);
// });

// // Return the updated cart as a response
// return response()->json([
//     'success' => true,
//     'data' => $cart,
// ]);