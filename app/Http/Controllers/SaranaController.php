<?php
namespace App\Http\Controllers;

use App\Models\Sarana;
use Illuminate\Http\Request;

class SaranaController extends Controller
{
    public function index()
    {
        $saranas = Sarana::all();
        // return view('sarana.index', compact('saranas'));
        return response()->json($saranas); // Placeholder until views are created
    }

    public function create()
    {
        // return view('sarana.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $sarana = Sarana::create($validated);
        // return redirect()->route('sarana.index')->with('success', 'Data created successfully.');
        return response()->json(['message' => 'Created successfully', 'data' => $sarana]);
    }

    public function show($id)
    {
        $sarana = Sarana::findOrFail($id);
        // return view('sarana.show', compact('sarana'));
        return response()->json($sarana);
    }

    public function edit($id)
    {
        $sarana = Sarana::findOrFail($id);
        // return view('sarana.edit', compact('sarana'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $sarana = Sarana::findOrFail($id);
        $sarana->update($validated);
        
        // return redirect()->route('sarana.index')->with('success', 'Data updated successfully.');
        return response()->json(['message' => 'Updated successfully', 'data' => $sarana]);
    }

    public function destroy($id)
    {
        $sarana = Sarana::findOrFail($id);
        $sarana->delete();
        
        // return redirect()->route('sarana.index')->with('success', 'Data deleted successfully.');
        return response()->json(['message' => 'Deleted successfully']);
    }
}