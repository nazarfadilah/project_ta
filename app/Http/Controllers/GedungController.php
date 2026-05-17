<?php
namespace App\Http\Controllers;

use App\Models\Gedung;
use Illuminate\Http\Request;

class GedungController extends Controller
{
    public function index()
    {
        $gedungs = Gedung::all();
        // return view('gedung.index', compact('gedungs'));
        return response()->json($gedungs); // Placeholder until views are created
    }

    public function create()
    {
        // return view('gedung.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $gedung = Gedung::create($validated);
        // return redirect()->route('gedung.index')->with('success', 'Data created successfully.');
        return response()->json(['message' => 'Created successfully', 'data' => $gedung]);
    }

    public function show($id)
    {
        $gedung = Gedung::findOrFail($id);
        // return view('gedung.show', compact('gedung'));
        return response()->json($gedung);
    }

    public function edit($id)
    {
        $gedung = Gedung::findOrFail($id);
        // return view('gedung.edit', compact('gedung'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $gedung = Gedung::findOrFail($id);
        $gedung->update($validated);
        
        // return redirect()->route('gedung.index')->with('success', 'Data updated successfully.');
        return response()->json(['message' => 'Updated successfully', 'data' => $gedung]);
    }

    public function destroy($id)
    {
        $gedung = Gedung::findOrFail($id);
        $gedung->delete();
        
        // return redirect()->route('gedung.index')->with('success', 'Data deleted successfully.');
        return response()->json(['message' => 'Deleted successfully']);
    }
}