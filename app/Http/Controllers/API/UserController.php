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
            'id' => 'required|integer',
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

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found with the given id',
                'data' => null
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'User fetched successfully',
            'data' => $user
        ], 200);
    }

    public function updateUser($id, Request $request)
    {

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found with the given id',
                'data' => null
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'error' => $validator->errors(),
                'data' => null
            ], 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ], 200);
    }

    public function deleteUser($id, Request $request)
    {

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found with the given id',
                'data' => null
            ], 400);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully',
            'data' => $user
        ], 200);
    }
}
