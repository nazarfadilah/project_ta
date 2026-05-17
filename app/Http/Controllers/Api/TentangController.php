<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TentangResource;
use App\Models\Tentang;
use Illuminate\Http\Request;

class TentangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tentangs = Tentang::all();
        return TentangResource::collection($tentangs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'key2' => 'required|string|max:255',
            'value' => 'required|string',
        ]);

        $tentang = Tentang::create($validated);
        return new TentangResource($tentang);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tentang $tentang)
    {
        return new TentangResource($tentang);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tentang $tentang)
    {
        $validated = $request->validate([
            'key' => 'sometimes|string|max:255',
            'key2' => 'sometimes|string|max:255',
            'value' => 'sometimes|string',
        ]);

        $tentang->update($validated);
        return new TentangResource($tentang);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tentang $tentang)
    {
        $tentang->delete();
        return response()->noContent();
    }
}
