<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ManageUserController extends Controller
{
    /**
     * Tampilkan daftar user (DataTables)
     */
    public function index()
    {
        $users = User::all();
        return view('main.user.index', compact('users'));
    }

    /**
     * Tampilkan form edit user
     */
    public function edit($email)
    {
        $user = User::where('email_users', $email)->firstOrFail();
        return view('main.user.form', compact('user'));
    }

    /**
     * Update data user
     */
    public function update(Request $request, $email)
    {
        $user = User::where('email_users', $email)->firstOrFail();

        $request->validate([
            'name_users' => 'required|string|max:128',
            'email_users' => 'required|email|max:128|unique:users,email_users,' . $email . ',email_users',
        ]);

        $user->update([
            'name_users' => $request->name_users,
            'email_users' => $request->email_users,
        ]);

        return redirect()->route('main.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }
}
