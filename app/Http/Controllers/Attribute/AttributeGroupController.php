<?php

namespace App\Http\Controllers\Attribute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttributeGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AttributeGroupController extends Controller
{
    /**
     * Retrieve all attribute groups from the system.
     *
     * This function retrieves and returns all attribute groups available in the system.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request object. (Note: The request is not used in this function, but it's part of the method signature.)
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response containing the list of attribute groups if successful.
     *                                      - If there are no attribute groups available, the 'attributeGroups' field will be an empty array.
     */
    public function index(Request $request): JsonResponse
    {
        $attributeGroups = AttributeGroup::with('attributes')->get();

        return response()->json([
            'success' => true,
            'attributeGroups' => $attributeGroups
        ]);
    }

    /**
     * Retrieve attributes belonging to an attribute group from the system.
     *
     * This function retrieves and returns all attributes associated with a specific attribute group identified by its ID.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request object. (Note: The request is not used in this function, but it's part of the method signature.)
     * @param  int  $id  The ID of the attribute group for which to retrieve attributes.
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response containing the attribute group and its associated attributes if successful.
     *                                      - If the attribute group with the given ID is found, 'success' will be true, and 'attributeGroup' will hold the attribute group details along with its attributes.
     *                                      - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If the attribute group with the given ID is not found, 'success' will be false, and 'message' will indicate 'Attribute group not found'.
     */
    public function getAttributesByAttributeGroup(Request $request, $id): JsonResponse
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

        $attributeGroup = AttributeGroup::with('attributes')->find($id);

        if (!$attributeGroup) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute group not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'attributeGroup' => $attributeGroup
        ]);
    }

    /**
     * Create a new attribute group in the system.
     *
     * This function creates a new attribute group with the provided 'name' and 'group_type' values.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request object containing the new attribute group data (name and group_type).
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response indicating the status of the creation operation.
     *                                      - If the attribute group creation is successful, 'success' will be true, and 'message' will hold a success message.
     *                                      - If validation fails for the 'name' and 'group_type', 'success' will be false, and 'errors' will contain the validation errors.
     */
    public function store(Request $request): JsonResponse
    {
        $validatorRules = [
            'name' => 'required|string|unique:attribute_groups,name',
            'group_type' => ['required', 'string', Rule::in(['select', 'radio'])]
        ];

        $validator = Validator::make($request->all(), $validatorRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        AttributeGroup::create([
            'name' => $request->name,
            'group_type' => $request->group_type,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attribute group created successfully'
        ]);
    }

    /**
     * Update an attribute group in the system.
     *
     * This function handles the update of an attribute group identified by its ID.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request object containing the updated attribute group data (ID, name, and group_type).
     * @param  int  $id  The ID of the attribute group to be updated.
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response indicating the status of the update operation.
     *                                      - If the attribute group update is successful, 'success' will be true, and 'message' will hold a success message.
     *                                      - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If the attribute group with the given ID is not found, 'success' will be false, and 'message' will indicate 'Attribute group not found'.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validatorRules = [
            'id' => 'required|integer',
            'name' => 'required|string',
            'group_type' => ['required', 'string', Rule::in(['select', 'radio'])]
        ];

        $validator = Validator::make(['id' => $id], $validatorRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $attributeGroup = AttributeGroup::find($id);

        if (!$attributeGroup) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute group not found'
            ], 404);
        }

        $attributeGroup->update([
            'name' => $request->name,
            'group_type' => $request->group_type,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attribute group updated successfully'
        ]);
    }

    /**
     * Delete an attribute group from the system.
     *
     * This function deletes an attribute group identified by its ID from the system.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request object. (Note: The request is not used in this function, but it's part of the method signature.)
     * @param  int  $id  The ID of the attribute group to be deleted.
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response indicating the status of the deletion operation.
     *                                      - If the attribute group with the given ID is found and successfully deleted, 'success' will be true, and 'message' will hold a success message.
     *                                      - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If the attribute group with the given ID is not found, 'success' will be false, and 'message' will indicate 'Attribute group not found'.
     */
    public function delete(Request $request, $id): JsonResponse
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

        $attributeGroup = AttributeGroup::find($id);

        if (!$attributeGroup) {
            return response()->json([
                'success' => false,
                'message' => 'Attribute group not found'
            ], 404);
        }

        $attributeGroup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attribute group deleted successfully'
        ]);
    }
}
