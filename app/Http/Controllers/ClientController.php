<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
                
        // Update user
        $client->update([
            'first_name' => $request->name,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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
