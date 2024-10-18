<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'description' => 'required|string',
            'role_id' => 'required|exists:roles,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $validated['profile_image'] = $imagePath;
        }

        $user = User::create($validated);

        return response()->json(['data' => $user->load('role'), 'message' => 'User created successfully!'], 201);
    }

    public function index()
    {
        $users = User::with('role')->get();
        return response()->json(['data' => $users]);
    }
}

