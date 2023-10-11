<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Doctrine\DBAL\Types\BlobType;
// Blob


class OrderController extends Controller
{
    
    /**
     * Create an order
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createOrder(Request $request)
    {
        $validationRules = [
            'cart_id' => 'required|integer|min:1|exists:carts,id',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            // Get status where name is 'Pending'
            $status = DB::table('statuses')->where('name', 'Pending')->first();

            // Get cart
            $cart = Cart::findOrFail($request->cart_id);

            $total_price = $cart->calculateTotalPrice();
            $total_cost = $cart->calculateTotalCost();

            // Generate Reference based on ULI + CURRENT YEAR + NUMBER OF ORDER IN CURRENT YEAR STARTING IN (000)  
            $reference = 'ULI' . date('Y') . str_pad(Order::whereYear('created_at', date('Y'))->count() + 1, 3, '0', STR_PAD_LEFT);

            $request->merge([
                'status_id' => $status->id,
                'reference' => $reference,
                'total_price' => $total_price,
                'total_cost' => $total_cost
            ]);

            /*
            * Validate variation stock is available
            */
            foreach ($cart->products as $product) {   
                if ($product->quantity > $product->variation->stock) {
                    return response()->json([
                        'success' => false,
                        'errors' => 'Product ' . $product->product->name . ' | SKU: ' . $product->variation->sku  . ' is out of stock'
                    ], 422);
                }
            }

            $order = Order::create($request->all());

            // Reduce product variation stock after create order
            foreach ($cart->products as $product) {
                $product->variation->stock -= $product->quantity;
                $product->variation->save();
            }
            
            // Set cart active status to false
            $cart->active = false;
            $cart->save();

            return response()->json([
                'success' => true,
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get an order
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getOrder(Request $request, int $id): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:orders,id'
        ];

        $validator = Validator::make(['id' => $id], $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $order = Order::with([
                'cart' => [
                    'products' => [
                        'variation' => [
                            'attributes' => function($query) {
                                $query->select('name', 'id');
                            }
                        ], 
                        'product'
                    ],
                    'user'
                ],
                'status'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get all orders
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrders(Request $request): JsonResponse
    {
        $validationRules = [
            'page' => 'integer|min:1',
            'perPage' => 'integer|min:1|max:100'
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $orders = Order::with([
            'cart' => [
                'products' => [
                    'variation' => [
                        'attributes' => function($query) {
                            $query->select('name', 'id');
                        }
                    ], 
                    'product'
                ],
                'user'
            ],
            'status'
            ])->paginate($request->perPage ?? 10)->toArray();
        $this->removeUnnecessaryPaginationData($orders);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Update an order
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateOrder(Request $request, int $id): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:orders,id',
            'reference' => 'sometimes|string|min:1|max:255',
            'cart_id' => 'sometimes|integer|min:1|exists:carts,id',
            'status_id' => 'sometimes|integer|min:1|exists:statuses,id',
        ];

        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {

            $order = Order::findOrFail($id);

            if($request->has('cart_id')) {
                // Get cart
                $cart = Cart::findOrFail($request->cart_id);

                $total_price = $cart->getTotalPrice();
                $total_cost = $cart->getTotalCost();

                $request->merge([
                    'total_price' => $total_price,
                    'total_cost' => $total_cost
                ]);
            }

            $order->update($request->all());

            return response()->json([
                'success' => true,
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an order
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deleteOrder(Request $request, int $id): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:orders,id'
        ];

        $validator = Validator::make(['id' => $id], $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $order = Order::findOrFail($id);

            // Soft delete the order
            $order->delete();

            return response()->json([
                'success' => true,
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }


    // generateInvoiceByOrderId return Blob object
    public function generateInvoiceByOrderId(Request $request, int $id)
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:orders,id'
        ];

        $validator = Validator::make(['id' => $id], $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $order = Order::with([
                'cart' => [
                    'products' => [
                        'variation' => [
                            'attributes' => function($query) {
                                $query->select('name', 'id');
                            }
                        ], 
                        'product'
                    ],
                    'user'
                ],
                'status'
            ])->findOrFail($id);


            $pdf = \PDF::loadView('invoice', ['order' => $order])->setPaper('a4', 'landscape')->setWarnings(false);

            return chunk_split(base64_encode($pdf->output()));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
