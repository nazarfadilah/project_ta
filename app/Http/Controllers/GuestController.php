<?php
namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::all();
        return view('main.tamu.index', compact('guests'));
    }

    public function create()
    {
        $guest = new Guest();
        $isDetail = false;
        return view('main.tamu.form', compact('guest', 'isDetail'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:16|unique:guest,nik',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:MALE,FEMALE',
            'phone' => 'nullable|numeric|digits_between:9,15',
            'address' => 'nullable|string',
            'bloodType' => 'nullable|string|max:5',
            'notes' => 'nullable|string',
        ]);

        Guest::create($validated);
        
        return redirect()->route('main.tamu.index')->with('success', 'Data tamu berhasil ditambahkan.');
    }

    public function show($id)
    {
        $guest = Guest::withCount('peminjamanTransaksis')->findOrFail($id);
        $isDetail = true;
        return view('main.tamu.form', compact('guest', 'isDetail'));
    }

    public function edit($id)
    {
        $guest = Guest::withCount('peminjamanTransaksis')->findOrFail($id);
        $isDetail = false;
        return view('main.tamu.form', compact('guest', 'isDetail'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:16|unique:guest,nik,'.$id,
            'name' => 'required|string|max:255',
            'gender' => 'required|in:MALE,FEMALE',
            'phone' => 'nullable|numeric|digits_between:9,15',
            'address' => 'nullable|string',
            'bloodType' => 'nullable|string|max:5',
            'notes' => 'nullable|string',
        ]);

        $guest = Guest::findOrFail($id);
        $guest->update($validated);
        
        // Sync to User if exists
        $user = User::where('guestId', $guest->id)->first();
        if ($user) {
            $user->update(['phone' => $guest->phone]);
        }
        
        return redirect()->route('main.tamu.index')->with('success', 'Data tamu berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();
        
        return redirect()->route('main.tamu.index')->with('success', 'Data tamu berhasil dihapus.');
    }
}