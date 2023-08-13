<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * This function retrieves and returns all clients available in the system.
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response containing the list of clients if successful.
     *                                      - If there are no clients available, the 'data' field will be an empty array.
     */
    public function index(): JsonResponse
    {
        // Get clients
        $clients = User::where('role', 'client')->get();
        return response()->json([
            'success' => true,
            'data' => $clients
        ], 200); 
    }

    /**
     * Store a newly created resource in storage.
     * 
     * This function creates a new client in the system.
     *
     * @param  \Illuminate\Http\Request  $request The HTTP request object.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the newly created client if successful.
     *                                     - If validation fails, 'success' will be false, and 'errors' will contain the validation errors.
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request
        $validatorRules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,client'
        ];

        $validator = Validator::make($request->all(), $validatorRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create user
        $client = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->get('phone_number'), // optional
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password)
        ]);
        
        // Response
        return response()->json([
            'success' => true,
            'data' => $client
        ], 201); 
    }

    /**
     * Display the specified resource.
     * 
     * This function retrieves and returns a specific client identified by its ID.
     *
     * @param  int  $id The ID of the client to retrieve.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the client if successful.
     *                                      - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If the client with the given ID is not found, 'success' will be false, and 'message' will indicate 'User not found'. 
     */
    public function show($id): JsonResponse
    {
        // Find user
        $client = User::find($id);

        // Validate user exists
        if(!$client) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
            
        // Response
        return response()->json([
            'success' => true,
            'data' => $client
        ], 200);    
    
    }

    /**
     * Update the specified resource in storage.
     * 
     * This function updates a specific client identified by its ID.
     *
     * @param  \Illuminate\Http\Request  $request The HTTP request object.
     * @param  int  $id The ID of the client to update.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the updated client if successful.
     *                                      - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                      - If the client with the given ID is not found, 'success' will be false, and 'message' will indicate 'User not found'.
     */
    public function update(Request $request, $id): JsonResponse
    {           
        // Find user
        $client = User::find($id);

        // Validate user exists
        if(!$client) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
                
        // Validate request
        $validatorRules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$client->id,
        ];

        $validator = Validator::make($request->all(), $validatorRules);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
                
        // Update user
        $client->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->get('phone_number'), // optional
        ]);
                
        // Response
        return response()->json([
            'success' => true,
            'data' => $client
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * This function deletes a specific client identified by its ID.
     *
     * @param  int  $id The ID of the client to delete.
     * @return \Illuminate\Http\Response A JSON response containing the deleted client if successful.
     *                                     - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.  
     *                                    - If the client with the given ID is not found, 'success' will be false, and 'message' will indicate 'User not found'.
     */
    public function destroy($id): JsonResponse
    {      
        // Find user
        $client = User::find($id);

        // Validate user exists
        if(!$client) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
                    
        // Delete user
        $client->delete();
                    
        // Response
        return response()->json([
            'success' => true,
            'data' => $client
        ], 200);
    }
}
