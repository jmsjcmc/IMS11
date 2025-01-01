<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
     public function viewAdminDashboard()
    {
        $users = User::all();
        return view('admin.admin-dashboard', compact('users'));
    }

    public function addUser()
    {
        return view('admin.adduser');
    }

    public function create(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->assignRole('User');
        return redirect()->route('admin.dashboard')->with('success', 'User created successfully');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'password' => [],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($request->only(['first_name', 'last_name', 'email', 'password']));
        return redirect()->route('admin.dashboard')->with('success','User updated successfully');
    }

    public function delete(User $user)
    {
        if($user->hasRole('Admin')){
            return back()->with('error', 'Cannot delete admin users');
        }
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully');
    }
}
