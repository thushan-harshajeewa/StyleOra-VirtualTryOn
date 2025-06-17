<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $userId = auth()->id(); // Get the logged-in user ID

        // Fetch the user's cart
        $cart = Cart::with('cartItems.productItem.productColor.product')->where('user_id', $userId)->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty',
            ]);
        }

        // Start the database transaction
        DB::beginTransaction();

        try {
            // Step 1: Create the order
            $order = Order::create([
                'user_id' => $userId,
                'status' => 'pending', // Default status is 'pending'
                'shipping_address' => $request->shipping_address,
                'phone'=>$request->phone,
                'city'=>$request->city,
                'postal_code'=>$request->postal_code,
                'total_price' => $request->net_total,
            ]);

            // Step 2: Create order items and decrement stock
            foreach ($cart->cartItems as $cartItem) {
                // Create the order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_item_id' => $cartItem->product_item_id,
                    'quantity' => $cartItem->quantity,

                ]);

                // Decrement stock for each product item
                $productItem = $cartItem->productItem;

                // Decrement stock using the built-in functionality of Eloquent
                // If stock goes negative, the database will throw an error
                $productItem->decrement('stock', $cartItem->quantity);
            }

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method, // e.g., credit_card, PayPal
                'transaction_id' => $order->id, // Unique transaction ID
                'amount' => $order->total_price,
                'status' => 'pending', // Default status is 'pending'
            ]);

            // Step 3: Clear the cart after creating the order
            $cart->cartItems()->delete(); // Delete all cart items

            // Commit the transaction
            DB::commit();

            

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully. Redirecting to PayPal...',
                
            ],200);
        } catch (Exception $e) {
            // If something goes wrong, roll back the transaction
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Order creation failed. Please try again.',
                'error' => $e->getMessage(),
            ],400);
        }
    }


    public function getOrderList()
{
    $userId = auth()->id(); // Get the logged-in user ID

    // Fetch the user's orders with related order items, product details, brand, category, and payment
    $orders = Order::with([
        'orderItems.productItem.productColor.product.brand',    // Include brand details
        'orderItems.productItem.productColor.product.category', // Include category details
        'payment' // Include payment details
    ])
    ->where('user_id', $userId)
    ->orderBy('created_at', 'desc') // Sort by most recent orders
    ->get();

    if ($orders->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No orders found.',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'user_id' => $order->user_id,
                'status' => $order->status,
                'total_price' => $order->total_price,
                'shipping_address' => $order->shipping_address,
                'created_at' => $order->created_at,
                'payment' => [
                    'payment_method' => $order->payment->payment_method,
                    'transaction_id' => $order->payment->transaction_id,
                    'amount' => $order->payment->amount,
                    'status' => $order->payment->status,
                ],
                'order_items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'order_id' => $item->order_id,
                        'product_item_id' => $item->product_item_id,
                        'quantity' => $item->quantity,
                        'product_item' => [
                            'id' => $item->productItem->id,
                            'product_color_id' => $item->productItem->product_color_id,
                            'size' => $item->productItem->size,
                            'price' => $item->productItem->price,
                            'stock' => $item->productItem->stock,
                            'product_color' => [
                                'id' => $item->productItem->productColor->id,
                                'color' => $item->productItem->productColor->color,
                                'product_picture' => $item->productItem->productColor->product_picture,
                                'product' => [
                                    'id' => $item->productItem->productColor->product->id,
                                    'name' => $item->productItem->productColor->product->name,
                                    'brand_id' => $item->productItem->productColor->product->brand_id,
                                    'category_id' => $item->productItem->productColor->product->category_id,
                                    'description' => $item->productItem->productColor->product->description,
                                    'main_image' => $item->productItem->productColor->product->main_image,
                                    'brand' => [
                                        'name' => $item->productItem->productColor->product->brand->name,
                                        'logo' => $item->productItem->productColor->product->brand->brand_image,
                                    ],
                                    'category' => [
                                        'name' => $item->productItem->productColor->product->category->name,
                                    ],
                                ],
                            ],
                        ],
                    ];
                }),
            ];
        }),
    ], 200);
}
    


}
