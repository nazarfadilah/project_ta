<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersProfilController extends Controller
{
    /**
     * Show the profile edit form for the logged-in user.
     */
    public function edit()
    {
        $user = auth()->user();
        
        // Auto-create a guest record if it doesn't exist yet
        if (!$user->guestId) {
            $nik = 'NIK' . sprintf('%05d', $user->id) . rand(1000, 9999);
            $guest = Guest::create([
                'nik' => substr($nik, 0, 16),
                'name' => $user->username,
                'gender' => 'MALE',
                'address' => '-',
            ]);
            $user->update(['guestId' => $guest->id]);
            $user->load('guest');
        } else {
            $user->load('guest');
        }

        $guest = $user->guest;

        return view('users.main.profil.form', compact('user', 'guest'));
    }

    /**
     * Update the logged-in user's profile and guest record.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $guestId = $user->guestId;

        // Auto-create guest if somehow missing
        if (!$guestId) {
            $nik = 'NIK' . sprintf('%05d', $user->id) . rand(1000, 9999);
            $guest = Guest::create([
                'nik' => substr($nik, 0, 16),
                'name' => $user->username,
                'gender' => 'MALE',
                'address' => '-',
            ]);
            $user->update(['guestId' => $guest->id]);
            $guestId = $guest->id;
        }

        $validated = $request->validate([
            // Guest fields
            'nik' => [
                'required',
                'digits:16',
                Rule::unique('guest', 'nik')->ignore($guestId),
            ],
            'name' => 'required|string|max:255',
            'gender' => 'required|in:MALE,FEMALE',
            'address' => 'nullable|string',
            'bloodType' => 'nullable|string|max:5',
            'notes' => 'nullable|string',
            
            // User fields
            'phone' => 'required|digits_between:9,15',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus berupa angka dan berjumlah tepat 16 digit.',
            'nik.unique' => 'NIK ini sudah terdaftar oleh pengguna lain.',
            'gender.in' => 'Jenis Kelamin harus berupa Pria atau Wanita.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.digits_between' => 'Nomor HP harus berupa angka dan berjumlah antara 9 sampai 15 digit.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal harus 6 karakter.',
        ]);

        // Update Guest record
        $guest = Guest::findOrFail($guestId);
        $guest->update([
            'nik' => $validated['nik'],
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'bloodType' => $validated['bloodType'],
            'notes' => $validated['notes'],
        ]);

        // Update User record (phone and optionally password)
        $userUpdate = [
            'phone' => $validated['phone'],
        ];

        if (!empty($validated['password'])) {
            $userUpdate['password'] = Hash::make($validated['password']);
        }

        $user->update($userUpdate);

        return redirect()->route('users.profil.edit')
            ->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
