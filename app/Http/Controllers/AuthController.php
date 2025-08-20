<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Services\TwilioVerifyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\RestException;



class AuthController extends Controller
{
    protected $twilio;

    public function __construct(TwilioVerifyService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function registerLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get validated data once
        $validated = $validator->validated();
        $phone = $validated['phone'];

        try {
            // Send OTP using Twilio
            $verification = $this->twilio->startVerification($phone, 'sms');

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully',
                'status'  => $verification->status, // usually 'pending'
            ], 200);
        } catch (RestException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'code'  => 'required'
        ]);

        $phoneNumber = $request->phone;
        $code = $request->code;

        try {
            $check = $this->twilio->checkVerification($phoneNumber, $code);

            if ($check->status === 'approved') {

                $user = User::where('phone', $request->phone)->first();

                if (!$user) {
                    // If not found, create the user
                    $user = User::create([
                        'phone' => $phoneNumber,
                    ]);

                    $user = User::where('phone', $request->phone)->first();
                }

                $token = $user->createToken($user->phone)->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Phone verified successfully',
                    'user' => User::getUser($user->id),
                    'token' => $token,
                    'token_type' => 'bearer'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        } catch (\Twilio\Exceptions\RestException $e) {
            if ($e->getStatusCode() === 404) {
                // Twilio says: no such verification found
                return response()->json([
                    'success' => false,
                    'message' => 'No active verification found. Please request a new OTP.'
                ], 400);
            }

            // For any other Twilio error
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->currentAccessToken()) {
            return response()->json([
                'success' => false,
                'message' => 'Token missing or invalid. Please login again.'
            ], 401);
        }

        // Delete the token
        $user->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function logoutAllDevice(Request $request)
    {
        $user = $request->user();

        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not logged in.'
            ], 401);
        }

        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function user(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => User::getUser($request->user()->id)
        ]);
    }
}
