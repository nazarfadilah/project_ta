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
        
        $gambarDashboards = GambarDashboard::paginate($perPage);

        return GambarDashboardResource::collection($gambarDashboards);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'posisi' => 'required|integer',
            'path' => 'required|string|max:255',
            'waktu_upload' => 'required|date_format:Y-m-d H:i:s',
            'updated_at' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $gambarDashboard = GambarDashboard::create($validated);
        return new GambarDashboardResource($gambarDashboard);
    }

    /**
     * Display the specified resource.
     */
    public function show(GambarDashboard $gambarDashboard)
    {
        return new GambarDashboardResource($gambarDashboard);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GambarDashboard $gambarDashboard)
    {
        $validated = $request->validate([
            'posisi' => 'sometimes|integer',
            'path' => 'sometimes|string|max:255',
            'waktu_upload' => 'sometimes|date_format:Y-m-d H:i:s',
            'updated_at' => 'sometimes|date_format:Y-m-d H:i:s',
        ]);

        $gambarDashboard->update($validated);
        return new GambarDashboardResource($gambarDashboard);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GambarDashboard $gambarDashboard)
    {
        $gambarDashboard->delete();
        return response()->noContent();
    }
}
