<?php
namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::all();
        // return view('ruangan.index', compact('ruangans'));
        return response()->json($ruangans); // Placeholder until views are created
    }

    public function create()
    {
        // return view('ruangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $ruangan = Ruangan::create($validated);
        // return redirect()->route('ruangan.index')->with('success', 'Data created successfully.');
        return response()->json(['message' => 'Created successfully', 'data' => $ruangan]);
    }

    public function show($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        // return view('ruangan.show', compact('ruangan'));
        return response()->json($ruangan);
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        // return view('ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $ruangan->update($validated);
        
        // return redirect()->route('ruangan.index')->with('success', 'Data updated successfully.');
        return response()->json(['message' => 'Updated successfully', 'data' => $ruangan]);
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();
        
        // return redirect()->route('ruangan.index')->with('success', 'Data deleted successfully.');
        return response()->json(['message' => 'Deleted successfully']);
    }
}