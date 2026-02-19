<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfilResource;
use App\Models\Profil;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        
        $profils = Profil::with('admin', 'user')->paginate($perPage);

        return ProfilResource::collection($profils);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $profil = Profil::create($request->all());
        return new ProfilResource($profil->load('admin', 'user'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Profil $profil)
    {
        return new ProfilResource($profil->load('admin', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profil $profil)
    {
        $profil->update($request->all());
        return new ProfilResource($profil->load('admin', 'user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profil $profil)
    {
        $profil->delete();
        return response()->json(['message' => 'Data profil berhasil dihapus']);
    }
}
