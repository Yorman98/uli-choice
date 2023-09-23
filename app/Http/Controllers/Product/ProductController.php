<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        $product = Product::with(['categories', 'variations.attributes'])->find($id);

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
            'categories' => 'nullable|array',
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

            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'code' => $request->code,
                'description' => $request->description
            ]);

            if ($request->has('categories')) {
                $product->categories()->attach($request->categories);
            }

            $product->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'product_id' => $product->id
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
     * Update a product
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request,int $id): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:products,id',
            'name' => 'nullable|string',
            'slug' => 'nullable|string|unique:products,slug,' . $id,
            'code' => 'nullable|string|unique:products,code,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'categories' => 'nullable|array',
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

            $product = Product::find($id);

            if ($request->has('name')) {
                $product->name = $request->name;
            }

            if ($request->has('slug')) {
                $product->slug = $request->slug;
            }

            if ($request->has('code')) {
                $product->code = $request->code;
            }

            if ($request->has('description')) {
                $product->description = $request->description;
            }

            if ($request->hasFile('image')) {
                $path = Storage::disk('public')->putFile('images/products', $request->file('image'));
                $product->image = $path;
            }

            if ($request->has('categories')) {
                $product->categories()->sync($request->categories);
            }

            $product->save();

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
     * Delete a product
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request,int $id): JsonResponse
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
            DB::beginTransaction();

            $product = Product::find($id);

            // Soft delete
            $product->delete();

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
}
