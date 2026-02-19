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
        $tentang = Tentang::create($request->all());
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
        $tentang->update($request->all());
        return new TentangResource($tentang);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tentang $tentang)
    {
        $tentang->delete();
        return response()->json(['message' => 'Data tentang berhasil dihapus']);
    }
}
