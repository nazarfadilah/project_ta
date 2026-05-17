<?php
namespace App\Http\Controllers;

use App\Models\DetailPeminjamanSarana;
use Illuminate\Http\Request;

class DetailPeminjamanSaranaController extends Controller
{
    public function index()
    {
        $detailPeminjamanSaranas = DetailPeminjamanSarana::all();
        // return view('detail_peminjaman_sarana.index', compact('detailPeminjamanSaranas'));
        return response()->json($detailPeminjamanSaranas); // Placeholder until views are created
    }

    public function create()
    {
        // return view('detail_peminjaman_sarana.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $detailPeminjamanSarana = DetailPeminjamanSarana::create($validated);
        // return redirect()->route('detail_peminjaman_sarana.index')->with('success', 'Data created successfully.');
        return response()->json(['message' => 'Created successfully', 'data' => $detailPeminjamanSarana]);
    }

    public function show($id)
    {
        $detailPeminjamanSarana = DetailPeminjamanSarana::findOrFail($id);
        // return view('detail_peminjaman_sarana.show', compact('detailPeminjamanSarana'));
        return response()->json($detailPeminjamanSarana);
    }

    public function edit($id)
    {
        $detailPeminjamanSarana = DetailPeminjamanSarana::findOrFail($id);
        // return view('detail_peminjaman_sarana.edit', compact('detailPeminjamanSarana'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $detailPeminjamanSarana = DetailPeminjamanSarana::findOrFail($id);
        $detailPeminjamanSarana->update($validated);
        
        // return redirect()->route('detail_peminjaman_sarana.index')->with('success', 'Data updated successfully.');
        return response()->json(['message' => 'Updated successfully', 'data' => $detailPeminjamanSarana]);
    }

    public function destroy($id)
    {
        $detailPeminjamanSarana = DetailPeminjamanSarana::findOrFail($id);
        $detailPeminjamanSarana->delete();
        
        // return redirect()->route('detail_peminjaman_sarana.index')->with('success', 'Data deleted successfully.');
        return response()->json(['message' => 'Deleted successfully']);
    }
}