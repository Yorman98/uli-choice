<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    /**
     * Get all transactions
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getTransactionsByOrderId(Request $request, int $id): JsonResponse
    {
        $validatorRules = [
            'id' => 'required|integer|exists:orders,id',
        ];

        $validator = Validator::make(['id' => $id], $validatorRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $transactions = Transaction::where('order_id', $id)->with('paymentMethod')->get();
        $order = Order::find($id);

        $totalPendingAmount = $order->total_price - $transactions->sum('amount');

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
            'total_paid' => $transactions->sum('amount'),
            'total_pending' => max($totalPendingAmount, 0)
        ]);
    }

    /**
     * Create transaction
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validationRules = [
            'order_id' => 'required|integer|exists:orders,id',
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if added amount is greater than order total
        $order = Order::find($request->order_id);
        $previousTransactionsAmount = Transaction::where('order_id', $request->order_id)->sum('amount');

        if ($previousTransactionsAmount + $request->amount > $order->total_price) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'amount' => 'Amount is greater than order total'
                ]
            ], 422);
        }

        Transaction::create($request->all());

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Edit transaction
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|exists:transactions,id',
            'payment_method_id' => 'sometimes|integer|exists:payment_methods,id',
            'amount' => 'sometimes|numeric|min:0',
            'notes' => 'sometimes|string'
        ];

        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $transaction = Transaction::findOrFail($id);

            if ($request->has('amount')) {
                $order = Order::find($transaction->order_id);
                $previousTransactionsAmount = Transaction::where('order_id', $transaction->order_id)->sum('amount');

                if ($previousTransactionsAmount - $transaction->amount + $request->amount > $order->total_price) {
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'amount' => 'Amount is greater than order total'
                        ]
                    ], 422);
                }
            }

            $transaction->update($request->all());

            DB::commit();

            return response()->json([
                'success' => true
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Delete transaction
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|exists:transactions,id'
        ];

        $validator = Validator::make(['id' => $id], $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->delete();

            return response()->json([
                'success' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}
