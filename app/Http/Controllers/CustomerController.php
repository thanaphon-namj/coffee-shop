<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'customer' => Customer::where('PhoneNo', $request->query('q'))->first(),
        ]);
    }
}
