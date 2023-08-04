<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{

    /**
     * Retrieve all providers from the system.
     *
     * This function retrieves and returns all providers available in the system.
     *
     * @param Request $request  The HTTP request object containing the optional filtering parameters (name and group_type).
     *
     * @return JsonResponse  A JSON response containing the list of providers if successful.
     *                                      - If 'name' and 'group_type' are provided, it will return all providers matching the specified filtering criteria.
     *                                      - If validation fails for the filtering parameters, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If there are no providers available, the 'providers' field will be an empty array.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('perPage', 10);

        $providers = Provider::paginate($perPage)->toArray();
        $this->removeUnnecessaryPaginationData($providers);

        return response()->json([
            'success' => true,
            'providers' => $providers
        ]);
    }

    /**
     * Create a new provider in the system.
     *
     * This function creates a new provider with the provided 'name' 
     *
     * @param Request $request  The HTTP request object containing the new provider data (name and attribute_group_id).
     *
     * @return JsonResponse  A JSON response indicating the status of the creation operation.
     *                                      - If the provider creation is successful, 'success' will be true, and 'message' will hold a success message.
     *                                      - If validation fails for the 'name' and 'attribute_group_id', 'success' will be false, and 'errors' will contain the validation errors.
     */
    public function store(Request $request): JsonResponse
    {
        $validatorRules = [
            'name' => 'required|string|unique:providers,name',
            'website' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $validatorRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        Provider::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Provider created successfully'
        ]);
    }

    /**
     * Get a provider
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $validationRules = [
            'id' => 'required|integer|min:1|exists:providers,id'
        ];

        $validator = Validator::make(['id' => $id], $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $provider = Provider::with(['purchases'])->find($id);

        return response()->json([
            'success' => true,
            'data' => $provider
        ]);
    }

    /**
     * Update a provider in the system.
     *
     * This function handles the update of a provider identified by its ID.
     *
     * @param Request $request  The HTTP request object containing the updated provider data (ID, name, and attribute_group_id).
     * @param  int  $id  The ID of the provider to be updated.
     *
     * @return JsonResponse  A JSON response indicating the status of the update operation.
     *                                      - If the provider update is successful, 'success' will be true, and 'message' will hold a success message.
     *                                      - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If the provider with the given ID is not found, 'success' will be false, and 'message' will indicate 'Provider not found'.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validatorRules = [
            'id' => 'required|integer',
            'name' => 'required|string',
            'website' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ];

        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), $validatorRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $provider = Provider::find($id);

        if (!$provider) {
            return response()->json([
                'success' => false,
                'message' => 'Provider not found'
            ], 404);
        }

        $provider->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Provider updated successfully'
        ]);
    }

    /**
     * Delete a provider from the system.
     *
     * This function handles the deletion of a provider identified by its ID.
     *
     * @param  int  $id  The ID of the provider to be deleted.
     *
     * @return JsonResponse  A JSON response indicating the status of the deletion operation.
     *                                      - If the provider deletion is successful, 'success' will be true, and 'message' will hold a success message.
     *                                      - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If the provider with the given ID is not found, 'success' will be false, and 'message' will indicate 'Provider not found'.
     */
    public function destroy($id): JsonResponse
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

        $provider = Provider::find($id);

        if (!$provider) {
            return response()->json([
                'success' => false,
                'message' => 'Provider not found'
            ], 404);
        }

        $provider->delete();

        return response()->json([
            'success' => true,
            'message' => 'Provider deleted successfully'
        ]);
    }


}
