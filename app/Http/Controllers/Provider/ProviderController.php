<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    

    public function index(Request $request): JsonResponse
    {
        $providers = Provider::all();

        return response()->json([
            'success' => true,
            'providers' => $providers
        ]);
    }


}
