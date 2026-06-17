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
        $user = User::where('email', $email)->firstOrFail();
        return view('main.user.form', compact('user'));
    }

    /**
     * Update data user
     */
    public function update(Request $request, $email)
    {
        $user = User::where('email', $email)->firstOrFail();

        $request->validate([
            'username' => 'required|string|max:128|unique:users,username,' . $user->id,
            'email' => 'required|email|max:128|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
        ]);

        return redirect()->route('main.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }
}
