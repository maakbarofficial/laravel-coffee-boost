<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function createUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'data' => null,
                'error' => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($user->id) {
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'data' => $user
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong',
            'data' => null
        ], 400);
    }

    public function getUsers()
    {
        $users = User::all();

        if ($users) {
            return response()->json([
                'status' => true,
                'message' => count($users) . ' Users fetched Successfully',
                'data' => $users
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong',
            'data' => null
        ], 400);
    }

    public function getUser($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'error' => $validator->errors(),
                'data' => null
            ], 422);
        }

        $user = User::find($id);

        return response()->json([
            'status' => true,
            'message' => 'User fetched successfully',
            'data' => $user
        ], 200);
    }
}
