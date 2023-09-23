<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductVariationController extends Controller
{
    /**
     * Get all variations of a product
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function index(Request $request, int $id): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:products,id'
        ];

        $validator = Validator::make(['id' => $id], $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $product = Product::findOrFail($id);

            $variations = $product->variations()->get()->map(function ($variation) {
                $variation->attributes = $variation->attributes()->get()->map(function ($attribute) {
                    $attribute->load('group');
                    return $attribute;
                });

                return $variation;
            });

            return response()->json([
                'success' => true,
                'product_id' => $id,
                'variations' => $variations ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update or create a variation of a product
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateOrCreate(Request $request, int $id): JsonResponse
    {
        $validationRules = [
            'id' => 'sometimes|integer|min:1|exists:variations,id',
            'product_id' => 'required|integer|min:1|exists:products,id',
            'sku' => 'required|string|min:1|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'attributes' => 'required|array|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validator = Validator::make(array_merge($request->all(), ['product_id' => $id]), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $image = null;
            if ($request->hasFile('image')) {
                $image = Storage::disk('public')->putFile('images/products', $request->file('image'));
            }

            $variation = Variation::updateOrCreate(
                ['id' => $request->id ?? null],
                [
                    'product_id' => $id,
                    'sku' => $request->sku,
                    'price' => $request->price,
                    'cost' => $request->cost,
                    'stock' => $request->stock,
                    'image' => $image
                ]
            );

            if ($request->has('attributes')) {
                $variation->attributes()->detach();
                $variation->attributes()->attach($request->get('attributes'));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'variation_id' => $variation->id
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
     * Delete a variation of a product
     *
     * @param Request $request
     * @param int $id
     * @param int $variationId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id, int $variationId): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:variations,id',
            'product_id' => 'required|integer|min:1|exists:products,id',
        ];

        $validator = Validator::make(['id' => $variationId, 'product_id' => $id], $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            DB::beginTransaction();

            Variation::where('id', $variationId)->where('product_id', $id)->delete();

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
