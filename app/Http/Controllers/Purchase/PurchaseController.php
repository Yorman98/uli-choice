<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{

    /**
     * Get all purchases
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('perPage', 10);

        $purchases = Purchase::with('provider')->paginate($perPage)->toArray();
        $this->removeUnnecessaryPaginationData($purchases);

        return response()->json([
            'success' => true,
            'data' => $purchases
        ]);
    }

    /**
     * Get purchases by provider ID
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getPurchasesByProviderID(Request $request, $id): JsonResponse
    {
        $validatorRules = [
            'id' => 'required|integer',
        ];

        $validator = Validator::make(['id' => $id], $validatorRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        //Paginate
        $perPage = $request->query('perPage', 10);

        $purchases = Purchase::where('provider_id', $id)->paginate($perPage)->toArray();
        $this->removeUnnecessaryPaginationData($purchases);

        return response()->json([
            'success' => true,
            'data' => $purchases
        ]);
    }


    /**
     * Get a purchase
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:purchases,id'
        ];

        $validator = Validator::make(['id' => $id], $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $purchase = Purchase::with('provider')->find($id);

        return response()->json([
            'success' => true,
            'data' => $purchase
        ]);
    }

    /**
     * Create a purchase
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validationRules = [
            'total' => 'required|numeric|min:0',
            'provider_id' => 'nullable|integer|exists:providers,id',
            'description' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        Purchase::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Purchase created successfully'
        ]);
    }

    /**
     * Update a purchase in the system.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request object containing the updated purchase data (ID, total, provider_id, and description).
     * @param  int  $id  The ID of the purchase to be updated.
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response indicating the status of the update operation.
     *                                      - If the purchase update is successful, 'success' will be true, and 'message' will hold a success message.
     *                                      - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If the purchase with the given ID is not found, 'success' will be false, and 'message' will indicate 'Purchase not found'.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validatorRules = [
            'id' => 'required|integer',
            'total' => 'required|numeric',
            'provider_id' => 'nullable|integer|exists:providers,id',
            'description' => 'nullable|string',
        ];

        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), $validatorRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $purchase = Purchase::find($id);

        if (!$purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase not found'
            ], 404);
        }

        $purchase->update([
            'total' => $request->total,
            'provider_id' => $request->provider_id,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Purchase updated successfully'
        ]);
    }

    /**
     * Delete a purchase in the system.
     *
     * @param  int  $id  The ID of the purchase to be deleted.
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response indicating the status of the delete operation.
     *                                      - If the purchase delete is successful, 'success' will be true, and 'message' will hold a success message.
     *                                      - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If the purchase with the given ID is not found, 'success' will be false, and 'message' will indicate 'Purchase not found'.
     */
    public function destroy($id): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:purchases,id'
        ];

        $validator = Validator::make(['id' => $id], $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $purchase = Purchase::find($id);

        if (!$purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase not found'
            ], 404);
        }

        $purchase->delete();

        return response()->json([
            'success' => true,
            'message' => 'Purchase deleted successfully'
        ]);
    }


}
