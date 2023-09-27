<?php

namespace App\Http\Controllers\ProductCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    /**
     * Retrieve all categories.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);

        $categories = Category::paginate($perPage)->toArray();
        $this->removeUnnecessaryPaginationData($categories);

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]); 
    }


    public function getAllCategoriesTree(Request $request)
    {
        $categories = Category::with('subcategories')->whereNull('category_id')->get();

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]); 
    }

    /**
     * Retrieve a specific category with its subcategories by ID.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(Request $request, $id)
    {
        $category = Category::with('subcategories', 'parent')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    /**
     * Store a new category in the database.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validatorRules = [
            'name' => 'required|string',
            'slug' => 'required|string|unique:categories,slug',
            'description' => 'nullable|string',
            'category_id' => 'integer|exists:categories,id|nullable' // This is the parent category ID
        ];

        $validator = Validator::make($request->all(), $validatorRules);
       
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::create($request->all());

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    /**
     * Update an existing category in the database by ID.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validatorRules = [
            'name' => 'string',
            'slug' => 'string|unique:categories,slug,' . $id,
            'description' => 'string|nullable',
            'category_id' => 'integer|exists:categories,id|nullable' // This is the parent category ID
        ];

        $validator = Validator::make($request->all(), $validatorRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    /**
     * Delete an existing category from the database by ID.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id) {

        $validatorRules = [
            'id' => 'required|integer|exists:categories,id'
        ];

        $validator = Validator::make(['id' => $id], $validatorRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        Category::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
