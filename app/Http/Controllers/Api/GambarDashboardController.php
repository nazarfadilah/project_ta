<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GambarDashboardResource;
use App\Models\GambarDashboard;
use Illuminate\Http\Request;

class GambarDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        
        $gambarDashboards = GambarDashboard::with('admin')->paginate($perPage);

        return GambarDashboardResource::collection($gambarDashboards);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $gambarDashboard = GambarDashboard::create($request->all());
        return new GambarDashboardResource($gambarDashboard->load('admin'));
    }

    /**
     * Display the specified resource.
     */
    public function show(GambarDashboard $gambarDashboard)
    {
        return new GambarDashboardResource($gambarDashboard->load('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GambarDashboard $gambarDashboard)
    {
        $gambarDashboard->update($request->all());
        return new GambarDashboardResource($gambarDashboard->load('admin'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GambarDashboard $gambarDashboard)
    {
        $gambarDashboard->delete();
        return response()->json(['message' => 'Gambar dashboard berhasil dihapus']);
    }
}
