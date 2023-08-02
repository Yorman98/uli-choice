<?php

namespace App\Http\Controllers\Attribute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;


class AttributeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', [
            'except' => ['login', 'register']
        ]);
    }
    
    /**
     * Retrieve all attributes from the system.
     *
     * This function retrieves and returns all attributes available in the system.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request object containing the optional filtering parameters (name and group_type).
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response containing the list of attributes if successful.
     *                                      - If 'name' and 'group_type' are provided, it will return all attributes matching the specified filtering criteria.
     *                                      - If validation fails for the filtering parameters, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If there are no attributes available, the 'attributes' field will be an empty array.
     */
    public function index(Request $request): JsonResponse
    {
        $attributes = Attribute::all();

        return response()->json([
            'success' => true,
            'attributes' => $attributes
        ]);
    }

    /**
     * Create a new attribute in the system.
     *
     * This function creates a new attribute with the provided 'name' and 'attribute_group_id' values.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request object containing the new attribute data (name and attribute_group_id).
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response indicating the status of the creation operation.
     *                                      - If the attribute creation is successful, 'success' will be true, and 'message' will hold a success message.
     *                                      - If validation fails for the 'name' and 'attribute_group_id', 'success' will be false, and 'errors' will contain the validation errors.
     */
    public function store(Request $request): JsonResponse
    {
        $validatorRules = [
            'name' => 'required|string',
            'attribute_group_id' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $validatorRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        Attribute::create([
            'name' => $request->name,
            'attribute_group_id' => $request->attribute_group_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attribute created successfully'
        ]);
    }

    /**
     * Show details of an attribute in the system.
     *
     * This function retrieves and displays the details of an attribute identified by its ID.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request object. (Note: The request is not used in this function, but it's part of the method signature.)
     * @param  int  $id  The ID of the attribute to be retrieved.
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response containing the attribute details if found.
     *                                      - If the attribute with the given ID exists, 'success' will be true, and 'attribute' will hold the attribute details.
     *                                      - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If the attribute with the given ID is not found, 'success' will be false, and 'message' will indicate 'Attribute not found'.
     */
    public function show(Request $request, $id): JsonResponse
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

        $attribute = Attribute::with('group')->find($id);

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'attribute' => $attribute
        ]);
    }

    /**
     * Update an attribute in the system.
     *
     * This function handles the update of an attribute identified by its ID.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request object containing the updated attribute data (ID, name, and attribute_group_id).
     * @param  int  $id  The ID of the attribute to be updated.
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response indicating the status of the update operation.
     *                                      - If the attribute update is successful, 'success' will be true, and 'message' will hold a success message.
     *                                      - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If the attribute with the given ID is not found, 'success' will be false, and 'message' will indicate 'Attribute not found'.
     */
     public function update(Request $request, $id): JsonResponse
     {
         $validatorRules = [
             'id' => 'required|integer',
             'name' => 'required|string',
             'attribute_group_id' => 'required|integer',
         ];
 
         $validator = Validator::make(array_merge($request->all(), ['id' => $id]), $validatorRules);
 
         if ($validator->fails()) {
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()
             ], 422);
         }
 
         $attribute = Attribute::find($id);
 
         if (!$attribute) {
             return response()->json([
                 'success' => false,
                 'message' => 'Attribute not found'
             ], 404);
         }
 
         $attribute->update([
             'name' => $request->name,
             'attribute_group_id' => $request->attribute_group_id,
         ]);
 
         return response()->json([
             'success' => true,
             'message' => 'Attribute updated successfully'
         ]);
     }
    
    

   
     /**
     * Delete an attribute from the system.
     *
     * This function deletes an attribute identified by its ID from the system.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request object. (Note: The request is not used in this function, but it's part of the method signature.)
     * @param  int  $id  The ID of the attribute to be deleted.
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response indicating the status of the deletion operation.
     *                                      - If the attribute with the given ID is found and successfully deleted, 'success' will be true, and 'message' will hold a success message.
     *                                      - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If the attribute with the given ID is not found, 'success' will be false, and 'message' will indicate 'Attribute not found'.
     */
    public function destroy(Request $request, $id): JsonResponse
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

        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute not found'
            ], 404);
        }

        $attribute->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attribute deleted successfully'
        ]);
    }
}
