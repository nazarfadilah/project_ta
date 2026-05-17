<?php
namespace App\Http\Controllers;

use App\Models\PaketRuangan;
use Illuminate\Http\Request;

class PaketRuanganController extends Controller
{
    public function index()
    {
        $paketRuangans = PaketRuangan::all();
        // return view('paket_ruangan.index', compact('paketRuangans'));
        return response()->json($paketRuangans); // Placeholder until views are created
    }

    public function create()
    {
        // return view('paket_ruangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $paketRuangan = PaketRuangan::create($validated);
        // return redirect()->route('paket_ruangan.index')->with('success', 'Data created successfully.');
        return response()->json(['message' => 'Created successfully', 'data' => $paketRuangan]);
    }

    public function show($id)
    {
        $paketRuangan = PaketRuangan::findOrFail($id);
        // return view('paket_ruangan.show', compact('paketRuangan'));
        return response()->json($paketRuangan);
    }

    public function edit($id)
    {
        $paketRuangan = PaketRuangan::findOrFail($id);
        // return view('paket_ruangan.edit', compact('paketRuangan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $paketRuangan = PaketRuangan::findOrFail($id);
        $paketRuangan->update($validated);
        
        // return redirect()->route('paket_ruangan.index')->with('success', 'Data updated successfully.');
        return response()->json(['message' => 'Updated successfully', 'data' => $paketRuangan]);
    }

    public function destroy($id)
    {
        $paketRuangan = PaketRuangan::findOrFail($id);
        $paketRuangan->delete();
        
        // return redirect()->route('paket_ruangan.index')->with('success', 'Data deleted successfully.');
        return response()->json(['message' => 'Deleted successfully']);
    }
}