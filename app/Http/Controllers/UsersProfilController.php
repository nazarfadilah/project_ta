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
            'instansi' => 'nullable|string|max:255',
            
            // User fields
            'phone' => 'required|digits_between:9,15',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|max:2048', // 2MB max
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus berupa angka dan berjumlah tepat 16 digit.',
            'nik.unique' => 'NIK ini sudah terdaftar oleh pengguna lain.',
            'gender.in' => 'Jenis Kelamin harus berupa Pria atau Wanita.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.digits_between' => 'Nomor HP harus berupa angka dan berjumlah antara 9 sampai 15 digit.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal harus 6 karakter.',
            'profile_photo.image' => 'Berkas harus berupa gambar.',
            'profile_photo.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        if (!$guestId) {
            $guest = Guest::create([
                'nik' => $validated['nik'],
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'bloodType' => $validated['bloodType'],
                'notes' => $validated['notes'],
                'phone' => $validated['phone'],
                'instansi' => $validated['instansi'] ?? null,
            ]);
            $user->update(['guestId' => $guest->id]);
        } else {
            $guest = Guest::findOrFail($guestId);
            $guest->update([
                'nik' => $validated['nik'],
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'bloodType' => $validated['bloodType'],
                'notes' => $validated['notes'],
                'phone' => $validated['phone'],
                'instansi' => $validated['instansi'] ?? null,
            ]);
        }

        // Update User record (phone and optionally password)
        $userUpdate = [
            'phone' => $validated['phone'],
        ];

        if ($request->hasFile('profile_photo')) {
            $photoPath = \App\Helpers\ImageHelper::optimizeAndStore(
                $request->file('profile_photo'),
                'profile_photos',
                300, // Max width
                300, // Max height
                80   // WebP quality
            );
            $userUpdate['profile_photo'] = $photoPath;
        }

        if (!empty($validated['password'])) {
            $userUpdate['password'] = Hash::make($validated['password']);
        }

        $user->update($userUpdate);

        return redirect()->route('users.profil.edit')
            ->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
