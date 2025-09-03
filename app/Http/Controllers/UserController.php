<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        // Dynamic validation rules based on role
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,inspector,viewer',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'certification_expiry' => 'nullable|date|after:today',
        ];

        // Make certification required for inspector and admin roles
        if (in_array($request->role, ['inspector', 'admin'])) {
            $rules['certification'] = 'required|string|min:10|max:1000';
        } else {
            $rules['certification'] = 'nullable|string|max:1000';
        }

        $request->validate($rules);

        // Prevent non-super-admin from creating super admin
        if ($request->role === 'super_admin' && !Auth::user()->isSuperAdmin()) {
            return back()->withErrors(['role' => 'You cannot create super admin users.']);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'department' => $request->department,
            'certification' => $request->certification,
            'certification_expiry' => $request->certification_expiry,
            'is_active' => true,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Prevent editing super admin unless you are super admin
        if ($user->isSuperAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403, 'You cannot edit super admin users.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // Prevent editing super admin unless you are super admin
        if ($user->isSuperAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403, 'You cannot edit super admin users.');
        }

        // Dynamic validation rules based on role
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,inspector,viewer',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'certification_expiry' => 'nullable|date|after:today',
            'is_active' => 'boolean',
        ];

        // Make certification required for inspector and admin roles
        if (in_array($request->role, ['inspector', 'admin'])) {
            $rules['certification'] = 'required|string|min:10|max:1000';
        } else {
            $rules['certification'] = 'nullable|string|max:1000';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'department' => $request->department,
            'certification' => $request->certification,
            'certification_expiry' => $request->certification_expiry,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting super admin
        if ($user->isSuperAdmin()) {
            return back()->withErrors(['error' => 'Super admin cannot be deleted.']);
        }

        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->withErrors(['error' => 'Super admin status cannot be changed.']);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }
}
