<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with staff user management
     */
    public function index()
    {
        Gate::authorize('isAdmin');
        
        // Get all staff users
        $staffUsers = User::where('role', 'staff')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.users.index', compact('staffUsers'));
    }

    /**
     * Store a newly created staff user
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin');
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'staff',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Staff user created successfully!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at->toIso8601String(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create staff user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified staff user
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('isAdmin');
        
        // Ensure we're only updating staff users
        if ($user->role !== 'staff') {
            return response()->json([
                'success' => false,
                'message' => 'You can only update staff users.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', Password::defaults(), 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Staff user updated successfully!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at->toIso8601String(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update staff user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified staff user
     */
    public function destroy(User $user)
    {
        $this->authorize('isAdmin');
        
        // Ensure we're only deleting staff users
        if ($user->role !== 'staff') {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete staff users.'
            ], 403);
        }

        try {
            $userName = $user->name;
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "Staff user '{$userName}' deleted successfully!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete staff user: ' . $e->getMessage()
            ], 500);
        }
    }
}
