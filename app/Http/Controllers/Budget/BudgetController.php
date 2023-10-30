<?php

namespace App\Http\Controllers\Budget;

use App\Models\Budget;
use App\Models\UserBudget;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * This function retrieves and returns all budgets available in the system.
     *
     * @return \Illuminate\Http\JsonResponse  A JSON response containing the list of budgets if successful. 
     *                                     - If there are no budgets available, the 'data' field will be an empty array.
     */
    public function index(Request $request): JsonResponse 
    {
        $search = $request->query('search');
        if($search) {
            // search by date, product_links (json), or user_id
            $budgets = Budget::join('user_budget', 'budgets.id', '=', 'user_budget.budget_id')
                ->join('users', 'user_budget.user_id', '=', 'users.id')
                ->orWhere('budgets.created_at', 'LIKE', "%{$search}%")
                ->orWhere('users.first_name', 'LIKE', "%{$search}%")
                ->get();
        }else{
            $budgets = Budget::all();
        }

        return response()->json([
            'success' => true,
            'data' => $budgets
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * This function creates a new budget in the system.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse A JSON response containing the newly created budget if successful. 
     *                                    - If validation fails, 'success' will be false, and 'errors' will contain the validation errors.
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request
        $validatorRules = [
            'product_links' => 'required|array',
            'user_id' => 'required|exists:users,id',
            'status_id' => 'required|exists:statuses,id',
            'price' => 'required',
            'cost' => 'required'
        ];
  
        $validator = Validator::make($request->all(), $validatorRules);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create budget
        $data = $request->all();
        $budget = Budget::create($data);

        // Attach user to budget
        $budget->user()->attach([$data['user_id']]);

        // Response
        return response()->json([
            'success' => true,
            'data' => $budget
        ], 201); 
    }

    /**
     * Display the specified resource.
     * 
     * This function retrieves and returns the specified budget if it exists.
     *
     * @param  int  $id The ID of the budget to retrieve.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the budget if successful.
     *                                   - If the budget does not exist, 'success' will be false, and 'message' will contain an error message.
     *                                   - If the budget exists, but the user does not have access to it, 'success' will be false, and 'message' will contain an error message.
     */
    public function show($id): JsonResponse
    {
        // Find budget
        $budget = Budget::find($id);

        // Validate budget exists 
        if(!$budget) {
            return response()->json([
                'success' => false,
                'message' => 'Budget not found'
            ], 404);
        }

        // Response
        return response()->json([
            'success' => true,
            'data' => $budget
        ], 200);  
    }

    /**
     * Update the specified resource in storage.
     * 
     * This function updates the specified budget if it exists.
     *
     * @param  \Illuminate\Http\Request  $request The HTTP request object.
     * @param  int  $id The ID of the budget to update.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the updated budget if successful.
     *                                     - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                     - If the budget does not exist, 'success' will be false, and 'message' will contain an error message.
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Find budget
        $budget = Budget::find($id);

        // Validate budget exists
        if(!$budget) {
            return response()->json([
                'success' => false,
                'message' => 'Budget not found'
            ], 404);
        }

        // Validate request 
        $validatorRules = [
            'user_id' => 'required|exists:users,id',
            'status_id' => 'required|exists:statuses,id',
        ];

        $validator = Validator::make($request->all(), $validatorRules);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Update budget
        $budget->update([
            'status_id' => $request->get('status_id'),
            'product_links' => $request->get('product_links'), // optional
            'price' => $request->get('price'), // optional
            'cost' => $request->get('cost'), // optional
            'message' => $request->get('msg') // optional
        ]);

        // Attach user to budget
        $budget->user()->sync([$request->user_id]);

        // If msg param is true, send an email to the user
        if($request->get('msg')) {
            // Get user
            $user = $budget->user()->first();

            try {
                // Send an email to the user
                Mail::raw($request->msg, function ($message) use ($user){
                    $message->to($user->email)
                    ->subject('Budget updated');
                });
            } catch (\Throwable $th) {
                // Log error
                echo $th->getMessage();
            }
        }

        // Response
        return response()->json([
            'success' => true,
            'data' => $budget
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * 
     * This function updates the specified budget if it exists and the user has access to it.
     *
     * @param  \Illuminate\Http\Request  $request The HTTP request object.
     * @param  int  $id The ID of the budget to update.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the updated budget if successful.
     *                                     - If validation fails for the ID, 'success' will be false, and 'errors' will contain the validation errors.
     *                                     - If the budget does not exist, 'success' will be false, and 'message' will contain an error message.
     */
    public function updateFromUser(Request $request, $id): JsonResponse
    {
        // Find budget
        $budget = Budget::find($id);

        // Validate budget exists
        if(!$budget) {
            return response()->json([
                'success' => false,
                'message' => 'Budget not found'
            ], 404);
        }

        // Validate request 
        $validatorRules = [
            'user_id' => 'required|exists:users,id',
            'product_links' => 'required|array',
        ];

        $validator = Validator::make($request->all(), $validatorRules);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Update budget
        $budget->update([
            'product_links' => $request->get('product_links')
        ]);

        // Attach user to budget
        $budget->user()->sync([$request->user_id]);

        // Response
        return response()->json([
            'success' => true,
            'data' => $budget
        ], 200);
    }
    
    /**
     * Remove the specified resource from storage.
     * 
     * This function deletes the specified budget if it exists.
     *
     * @param  int  $id The ID of the budget to delete.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the deleted budget if successful.
     *                                    - If the budget does not exist, 'success' will be false, and 'message' will contain an error message.
     *                                    - If the budget exists, but the user does not have access to it, 'success' will be false, and 'message' will contain an error message.
     */
    public function destroy($id): JsonResponse
    {
        // Find budget
        $budget = Budget::find($id);

        // Validate budget exists
        if(!$budget) {
            return response()->json([
                'success' => false,
                'message' => 'Budget not found'
            ], 404);
        }

        // Delete budget
        $budget->delete();

        // Response 
        return response()->json([
            'success' => true,
            'data' => $budget
        ], 200);
    }
}
