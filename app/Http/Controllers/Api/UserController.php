<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        
        $users = User::with('profil')->paginate($perPage);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());
        return new UserResource($user->load('profil'));
    }

    /**
     * Display the specified resource.
     */
    public function show($email_users)
    {
        $user = User::with('profil')->findOrFail($email_users);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $email_users)
    {
        $user = User::findOrFail($email_users);
        $user->update($request->all());
        return new UserResource($user->load('profil'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($email_users)
    {
        $user = User::findOrFail($email_users);
        $user->delete();
        return response()->json(['message' => 'Data user berhasil dihapus']);
    }
}
