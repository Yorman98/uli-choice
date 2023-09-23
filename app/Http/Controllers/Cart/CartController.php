<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductCart;
use App\Models\Variation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getCartProducts(Request $request, int $id): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:carts,id'
        ];

        $validator = Validator::make(['id' => $id], $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cart = Cart::findOrFail($id);

            $products = $this->mapProducts($cart->products()->get()) ?? [];

            return response()->json([
                'success' => true,
                'cart_id' => $id,
                'products' => $products,
                'total_price' => $this->calculateCartTotal($products)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 404);
        }
    }


    /**
     * Add product to cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addProductToCart(Request $request): JsonResponse
    {
        $validationRules = [
            'product_id' => 'required|integer|exists:products,id',
            'variation_id' => 'required|integer|exists:variations,id',
            'quantity' => 'required|integer|min:1',
            'user_id' => 'sometimes|integer|exists:users,id'
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // TODO: Implement cart with session for non-logged in users

            if (auth('sanctum')->check()) {
                $user_id = $request->user_id ?? auth('sanctum')->user()->id;

                // Get active cart from database
                $cart = Cart::where('user_id', $user_id)->where('active', true)->first();

                // If no active cart, create one
                if (!$cart) {
                    $cart = Cart::create([
                        'user_id' => $user_id,
                        'active' => true
                    ]);
                }

                // Check if product or variation is already in cart
                $product = $cart->products()->where('product_id', $request->product_id)
                    ->where('variation_id', $request->variation_id ?? null)->first();

                if ($product) {
                    $product->quantity += $request->quantity;
                    $product->save();
                } else {
                    // TODO: possibly add product to cart with no variation

                    // Get variation
                    $variation = Variation::find($request->variation_id);

                    // Validate if variation belongs to product_id
                    if ($variation->product_id != $request->product_id) {
                        return response()->json([
                            'success' => false,
                            'errors' => 'Variation does not belong to product'
                        ], 422);
                    }

                    $product_cart = new ProductCart([
                        'product_id' => $request->product_id,
                        'variation_id' => $request->variation_id,
                        'quantity' => $request->quantity,
                        'unit_price' => $variation->price,
                        'unit_cost' => $variation->cost
                    ]);

                    // Add product to cart
                    $cart->products()->save($product_cart);
                }

                DB::commit();

                $products = $this->mapProducts($cart->products()->get()) ?? [];

                return response()->json([
                    'success' => true,
                    'cart_id' => $cart->id,
                    'products' => $products,
                    'total_price' => $this->calculateCartTotal($products)
                ]);
            }

            return response()->json([
                'success' => false
            ], 403);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Map products with standard fields for product with or without variation
     *
     * @param $products
     * @return mixed
     */
    private function mapProducts($products): mixed
    {
        return $products->map(function ($productCart) {
            $productCart->load('variation');
            $productCart->load('product');

            return [
                'id' => $productCart->id,
                'product_id' => $productCart->product_id,
                'variation_id' => $productCart->variation_id,
                'quantity' => $productCart->quantity,
                'unit_price' => $productCart->unit_price,
                'unit_cost' => $productCart->unit_cost,
                'image' => $productCart->variation ? $productCart->variation->image : $productCart->product->image,
                'name'  => $productCart->product->name,
            ];
        });
    }

    /**
     * Calculate cart total
     *
     * @param $products
     * @return float|int
     */
    private function calculateCartTotal($products): float|int
    {
        $total = 0;

        foreach ($products as $product) {
            $total += $product['unit_price'] * $product['quantity'];
        }

        return $total;
    }

    /**
     * Remove product from cart
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function removeProductFromCart(Request $request, int $id): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:product_cart,id'
        ];

        $validator = Validator::make(['id' => $id], $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $productCart = ProductCart::findOrFail($id);

            $productCart->delete();

            DB::commit();

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product quantity in cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateQuantityToProduct(Request $request): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:product_cart,id',
            'quantity' => 'required|integer|min:1'
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $productCart = ProductCart::findOrFail($request->id);

            $productCart->quantity = $request->quantity;

            $productCart->save();

            DB::commit();

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
