<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // default role
        ]);

        return response()->json([
            'success' => 'User created successfully.',
            'user' => $user, // Trả về người dùng đã tạo
        ], 201); // 201 Created
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'There was an error creating the user.',
            'message' => $e->getMessage() // Có thể log lỗi chi tiết để debug
        ], 500);
    }
}
}
