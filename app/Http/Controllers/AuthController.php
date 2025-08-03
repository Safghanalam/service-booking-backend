<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Validate the data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:4',
            'phone' => 'nullable|string',
            'gender' => 'required|string|in:male,female,other,Male,Female,Other',
            'age' => 'required|integer|min:0'
        ]);

        // Response if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get validated data once
        $data = $validator->validated();

        // Add/override values
        $data['role_id'] = 2;

        // Get user object after creation
        $user = User::create($data);
        // Generate auth token for authenticated operations
        $token = $user->createToken($user->first_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => array_merge($user->toArray(), ['token' => $token])
        ]);
    }


    public function login(Request $request)
    {
        return ['message' => 'Login Function'];
    }
}
