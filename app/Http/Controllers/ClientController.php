<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            'message' => 'List of clients',
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
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required'
        ]);
        
        // Create user
        $client = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->get('phone_number'), // optional
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password)
        ]);
        
        // Create token
        $token = $client->createToken('auth_token')->plainTextToken;
        
        // Response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => [
                'user' => $client,
                'token' => $token
            ]
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
            'message' => 'User found',
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
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$client->id,
        ]);
                
        // Update user
        $client->update([
            'first_name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);
                
        // Response
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
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
            'message' => 'User deleted successfully',
            'data' => $client
        ], 200);
    }
}
