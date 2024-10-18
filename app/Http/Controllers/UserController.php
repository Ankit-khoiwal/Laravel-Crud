<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('users', compact('roles'));
    }

    public function getUsers(Request $request)
    {
        $users = User::with('role')->paginate(10);
        return response()->json([
            'data' => $users->items(),
            'links' => [
                'next' => $users->nextPageUrl(),
                'prev' => $users->previousPageUrl(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ],
            'total' => $users->total()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'description' => 'required|string',
            'role_id' => 'required|exists:roles,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'email', 'phone', 'description', 'role_id']);

        if ($request->hasFile('profile_image')) {
            $uploadPath = public_path('upload');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $image = $request->file('profile_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($uploadPath, $imageName);
            $data['profile_image'] = 'upload/' . $imageName;
        }

        $user = User::create($data);
        return response()->json(['message' => 'User created successfully!', 'data' => $user->load('role')]);
    }
}
