<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected $fileController;

    public function __construct()
    {
        $this->fileController = new FileController;
    }

    public function updateUser(Request $request)
    {
        // Validaiton rule
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|min:3|string',
            'last_name'  => 'nullable|min:3|string',
            'email'      => 'nullable|email|unique:users,email,' . $request->user()->id,
            'phone'      => 'required|max:13',
            'gender'     => 'nullable|string|in:male,female,Male,Female,Other,other',
            'age'        => 'nullable|integer',
            'avatar'     => 'nullable|image|mimes:jpg,jpeg,webp,png|max:2048',
        ]);

        // Validate data
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vaidated data
        $validated = $validator->validate();

        // Uploading avatar if available
        if ($request->file('avatar')) {
            $avatarTempPath = $request->file('avatar')->getRealPath();
            $avatarPath = $this->fileController->uploadUserAvatar($request->user(), $avatarTempPath);

            $validated['avatar'] = $avatarPath;
        }

        // Updating data
        $request->user()->update($validated);

        // Response
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => User::getUser($request->user()->id)
        ]);
    }

    public function deleteUserRequest(Request $request)
    {
        // Validation user logged in or not ?
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not logged in.'
            ], 401);
        }

        // Soft delete
        $request->user()->update('is_deleted');

        // Delete all bearer tokens
        $request->user()->tokens()->delete();

        // Response
        return response()->json([
            'success' => true,
            'message' => "User successfully deleted",
            'user' => User::getUser($request->user()->id)
        ]);
    }
}
