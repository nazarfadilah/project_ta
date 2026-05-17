<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        // return view('invoice.index', compact('invoices'));
        return response()->json($invoices); // Placeholder until views are created
    }

    public function create()
    {
        // return view('invoice.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $invoice = Invoice::create($validated);
        // return redirect()->route('invoice.index')->with('success', 'Data created successfully.');
        return response()->json(['message' => 'Created successfully', 'data' => $invoice]);
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        // return view('invoice.show', compact('invoice'));
        return response()->json($invoice);
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        // return view('invoice.edit', compact('invoice'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Add validation rules here
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->update($validated);
        
        // return redirect()->route('invoice.index')->with('success', 'Data updated successfully.');
        return response()->json(['message' => 'Updated successfully', 'data' => $invoice]);
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        
        // return redirect()->route('invoice.index')->with('success', 'Data deleted successfully.');
        return response()->json(['message' => 'Deleted successfully']);
    }
}