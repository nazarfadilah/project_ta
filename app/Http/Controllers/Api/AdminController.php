<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        
        $admins = Admin::with('gedung')->paginate($perPage);

        return AdminResource::collection($admins);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $admin = Admin::create($request->all());
        return new AdminResource($admin->load('gedung'));
    }

    /**
     * Display the specified resource.
     */
    public function show($email_admin)
    {
        $admin = Admin::with('gedung')->findOrFail($email_admin);
        return new AdminResource($admin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $email_admin)
    {
        $admin = Admin::findOrFail($email_admin);
        $admin->update($request->all());
        return new AdminResource($admin->load('gedung'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($email_admin)
    {
        $admin = Admin::findOrFail($email_admin);
        $admin->delete();
        return response()->json(['message' => 'Data admin berhasil dihapus']);
    }
}
