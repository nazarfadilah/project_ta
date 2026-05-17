<?php
namespace App\Http\Controllers;

use App\Models\MediaFile;
use Illuminate\Http\Request;

class MediaFileController extends Controller
{
    public function index()
    {
        $mediaFiles = MediaFile::all();
        // return view('media_file.index', compact('mediaFiles'));
        return response()->json($mediaFiles); // Placeholder until views are created
    }

    public function create()
    {
        // return view('media_file.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $mediaFile = MediaFile::create($validated);
        // return redirect()->route('media_file.index')->with('success', 'Data created successfully.');
        return response()->json(['message' => 'Created successfully', 'data' => $mediaFile]);
    }

    public function show($id)
    {
        $mediaFile = MediaFile::findOrFail($id);
        // return view('media_file.show', compact('mediaFile'));
        return response()->json($mediaFile);
    }

    public function edit($id)
    {
        $mediaFile = MediaFile::findOrFail($id);
        // return view('media_file.edit', compact('mediaFile'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $mediaFile = MediaFile::findOrFail($id);
        $mediaFile->update($validated);
        
        // return redirect()->route('media_file.index')->with('success', 'Data updated successfully.');
        return response()->json(['message' => 'Updated successfully', 'data' => $mediaFile]);
    }

    public function destroy($id)
    {
        $mediaFile = MediaFile::findOrFail($id);
        $mediaFile->delete();
        
        // return redirect()->route('media_file.index')->with('success', 'Data deleted successfully.');
        return response()->json(['message' => 'Deleted successfully']);
    }
}