<?php
namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        // return view('role.index', compact('roles'));
        return response()->json($roles); // Placeholder until views are created
    }

    public function create()
    {
        // return view('role.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $role = Role::create($validated);
        // return redirect()->route('role.index')->with('success', 'Data created successfully.');
        return response()->json(['message' => 'Created successfully', 'data' => $role]);
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        // return view('role.show', compact('role'));
        return response()->json($role);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        // return view('role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $role = Role::findOrFail($id);
        $role->update($validated);
        
        // return redirect()->route('role.index')->with('success', 'Data updated successfully.');
        return response()->json(['message' => 'Updated successfully', 'data' => $role]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        
        // return redirect()->route('role.index')->with('success', 'Data deleted successfully.');
        return response()->json(['message' => 'Deleted successfully']);
    }
}