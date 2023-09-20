<?php

namespace App\Http\Controllers\product;

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

        $perPage = $request->query('perPage', 10);

        $product = Product::find($id);
        $variations = $product->variations()->paginate($perPage)->toArray();
        $this->removeUnnecessaryPaginationData($variations);

        return response()->json([
            'success' => true,
            'product_id' => $id,
            'variations' => $variations
        ]);
    }

    /**
     * Get a variation of a product
     *
     * @param Request $request
     * @param int $id
     * @param int $variationId
     * @return JsonResponse
     */
    public function show(Request $request, int $id, int $variationId): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:products,id',
            'variationId' => 'required|integer|min:1|exists:variations,id'
        ];

        $validator = Validator::make(['id' => $id, 'variationId' => $variationId], $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $variation = Variation::find($variationId);

        return response()->json([
            'success' => true,
            'data' => $variation
        ]);
    }

    /**
     * Create a variation of a product
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function store(Request $request, int $id): JsonResponse
    {
        $validationRules = [
            'product_id' => 'required|integer|min:1|exists:products,id',
            'sku' => 'required|string|min:1|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];

        $validator = Validator::make(array_merge($request->all(), ['product_id' => $id]), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        Variation::create([
            'product_id' => $id,
            'sku' => $request->sku,
            'price' => $request->price,
            'cost' => $request->cost,
            'stock' => $request->stock,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Variation created successfully'
        ]);
    }

    /**
     * Update a variation of a product
     *
     * @param Request $request
     * @param int $id
     * @param int $variationId
     * @return JsonResponse
     */
    public function update(Request $request, int $id, int $variationId): JsonResponse
    {

        $validationRules = [
            'id' => 'required|integer|min:1|exists:variations,id',
            'product_id' => 'required|integer|min:1|exists:products,id',
            'sku' => 'required|string|min:1|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];

        $validator = Validator::make(array_merge($request->all(), ['id' => $variationId, 'product_id' => $id]), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        Variation::where('id', $variationId)->update([
            'sku' => $request->sku,
            'price' => $request->price,
            'cost' => $request->cost,
            'stock' => $request->stock,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Variation updated successfully'
        ]);
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

        Variation::where('id', $variationId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Variation deleted successfully'
        ]);
    }


    /**
     * Attach attribute ID to variation
     */
    function addAttributeToVariation(Request $request, int $id, int $variationId)
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:variations,id',
            'product_id' => 'required|integer|min:1|exists:products,id',
            'attribute_id' => 'required|integer|min:1|exists:attributes,id'        ];

        $validator = Validator::make(array_merge($request->all(), ['id' => $variationId, 'product_id' => $id]), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $variation = Variation::find($variationId);
        $variation->attributes()->attach($request->attribute_id);

        return response()->json([
            'success' => true,
            'message' => 'Attribute attached successfully'
        ]);
    }

    /**
     * Detach attribute ID from variation
     */
    function removeAttributeFromVariation(Request $request, int $id, int $variationId, int $idAttribute)
    {
        // Validate Composite for 'variation_attribute' where clause
        $validationRules = [
            'product_id' => 'required|integer|min:1|exists:products,id',
            'variation_id' => [
                'required',
                'integer',
                'min:1',
                'exists:variations,id'                
            ],
            'attribute_id' => [
                'required',
                'integer',
                'min:1',
                'exists:attributes,id',
                Rule::exists('variation_attribute')->where(function ($query) use ($variationId, $idAttribute) {
                    return $query->where('variation_id', $variationId)
                                 ->where('attribute_id', $idAttribute);
                }),
            ]
        ];


        $validator = Validator::make(array_merge($request->all(), ['variation_id' => $variationId, 'product_id' => $id, 'attribute_id' => $idAttribute]), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $variation = Variation::find($variationId);

        $variation->attributes()->detach($idAttribute);

        return response()->json([
            'success' => true,
            'message' => 'Attribute detached successfully'
        ]);
    }

}
