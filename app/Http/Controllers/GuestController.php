<?php
namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::all();
        // return view('guest.index', compact('guests'));
        return response()->json($guests); // Placeholder until views are created
    }

    public function create()
    {
        // return view('guest.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $guest = Guest::create($validated);
        // return redirect()->route('guest.index')->with('success', 'Data created successfully.');
        return response()->json(['message' => 'Created successfully', 'data' => $guest]);
    }

    public function show($id)
    {
        $guest = Guest::findOrFail($id);
        // return view('guest.show', compact('guest'));
        return response()->json($guest);
    }

    public function edit($id)
    {
        $guest = Guest::findOrFail($id);
        // return view('guest.edit', compact('guest'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $guest = Guest::findOrFail($id);
        $guest->update($validated);
        
        // return redirect()->route('guest.index')->with('success', 'Data updated successfully.');
        return response()->json(['message' => 'Updated successfully', 'data' => $guest]);
    }

    public function destroy($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();
        
        // return redirect()->route('guest.index')->with('success', 'Data deleted successfully.');
        return response()->json(['message' => 'Deleted successfully']);
    }
}