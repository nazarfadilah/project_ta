<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activityLogs = ActivityLog::all();
        // return view('activity_log.index', compact('activityLogs'));
        return response()->json($activityLogs); // Placeholder until views are created
    }

    public function create()
    {
        // return view('activity_log.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $activityLog = ActivityLog::create($validated);
        // return redirect()->route('activity_log.index')->with('success', 'Data created successfully.');
        return response()->json(['message' => 'Created successfully', 'data' => $activityLog]);
    }

    public function show($id)
    {
        $activityLog = ActivityLog::findOrFail($id);
        // return view('activity_log.show', compact('activityLog'));
        return response()->json($activityLog);
    }

    public function edit($id)
    {
        $activityLog = ActivityLog::findOrFail($id);
        // return view('activity_log.edit', compact('activityLog'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $activityLog = ActivityLog::findOrFail($id);
        $activityLog->update($validated);
        
        // return redirect()->route('activity_log.index')->with('success', 'Data updated successfully.');
        return response()->json(['message' => 'Updated successfully', 'data' => $activityLog]);
    }

    public function destroy($id)
    {
        $activityLog = ActivityLog::findOrFail($id);
        $activityLog->delete();
        
        // return redirect()->route('activity_log.index')->with('success', 'Data deleted successfully.');
        return response()->json(['message' => 'Deleted successfully']);
    }
}