<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentMethodsController extends Controller
{
    /**
     * Get all payment methods
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => PaymentMethod::all()
        ]);
    }

    /**
     * Create payment method
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validationRules = [
            'name' => 'required|string|max:255'
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $paymentMethod = PaymentMethod::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $paymentMethod
        ]);
    }

    /**
     * Soft delete a payment method
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function softDelete(Request $request, int $id): JsonResponse
    {
        $paymentMethod = PaymentMethod::find($id);

        if (!$paymentMethod) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'payment_method' => 'Payment method not found'
                ]
            ], 404);
        }

        $paymentMethod->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
