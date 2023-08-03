<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Get all products
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
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

        $products = Product::paginate($request->perPage ?? 10)->toArray();
        $this->removeUnnecessaryPaginationData($products);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get a product
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
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

        $product = Product::find($id);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Create a product
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validationRules = [
            'name' => 'required|string',
            'slug' => 'required|string|unique:products,slug',
            'code' => 'required|string|unique:products,code',
            'description' => 'nullable|string',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'code' => $request->code,
            'description' => $request->description
        ]);

        // TODO: Upload image, if any

        // TODO: Associate product with categories, if any

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }
}
