<?php
namespace App\Http\Controllers;

use App\Models\PeminjamanTransaksi;
use Illuminate\Http\Request;

class PeminjamanTransaksiController extends Controller
{
    public function index()
    {
        $peminjamanTransaksis = PeminjamanTransaksi::all();
        // return view('peminjaman_transaksi.index', compact('peminjamanTransaksis'));
        return response()->json($peminjamanTransaksis); // Placeholder until views are created
    }

    public function create()
    {
        // return view('peminjaman_transaksi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $peminjamanTransaksi = PeminjamanTransaksi::create($validated);
        // return redirect()->route('peminjaman_transaksi.index')->with('success', 'Data created successfully.');
        return response()->json(['message' => 'Created successfully', 'data' => $peminjamanTransaksi]);
    }

    public function show($id)
    {
        $peminjamanTransaksi = PeminjamanTransaksi::findOrFail($id);
        // return view('peminjaman_transaksi.show', compact('peminjamanTransaksi'));
        return response()->json($peminjamanTransaksi);
    }

    public function edit($id)
    {
        $peminjamanTransaksi = PeminjamanTransaksi::findOrFail($id);
        // return view('peminjaman_transaksi.edit', compact('peminjamanTransaksi'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $peminjamanTransaksi = PeminjamanTransaksi::findOrFail($id);
        $peminjamanTransaksi->update($validated);
        
        // return redirect()->route('peminjaman_transaksi.index')->with('success', 'Data updated successfully.');
        return response()->json(['message' => 'Updated successfully', 'data' => $peminjamanTransaksi]);
    }

    public function destroy($id)
    {
        $peminjamanTransaksi = PeminjamanTransaksi::findOrFail($id);
        $peminjamanTransaksi->delete();
        
        // return redirect()->route('peminjaman_transaksi.index')->with('success', 'Data deleted successfully.');
        return response()->json(['message' => 'Deleted successfully']);
    }
}