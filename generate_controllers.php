<?php

$controllers = [
    'ActivityLogController',
    'DetailPeminjamanSaranaController',
    'GedungController',
    'GuestController',
    'InvoiceController',
    'MediaFileController',
    'PaketRuanganController',
    'PeminjamanTransaksiController',
    'RoleController',
    'RuanganController',
    'SaranaController'
];

foreach ($controllers as $controller) {
    $modelName = str_replace('Controller', '', $controller);
    $viewDir = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $modelName));
    $varName = lcfirst($modelName);
    $varNamePlural = $varName . 's';

    $content = <<<EOT
<?php
namespace App\Http\Controllers;

use App\Models\\$modelName;
use Illuminate\Http\Request;

class $controller extends Controller
{
    public function index()
    {
        \$$varNamePlural = $modelName::latest()->paginate(10);
        // return view('$viewDir.index', compact('$varNamePlural'));
        return response()->json(\$$varNamePlural); // Placeholder until views are created
    }

    public function create()
    {
        // return view('$viewDir.create');
    }

    public function store(Request \$request)
    {
        \$validated = \$request->validate([
            // Add validation rules here
        ]);

        \$$varName = $modelName::create(\$validated);
        // return redirect()->route('$viewDir.index')->with('success', 'Data created successfully.');
        return response()->json(['message' => 'Created successfully', 'data' => \$$varName]);
    }

    public function show(\$id)
    {
        \$$varName = $modelName::findOrFail(\$id);
        // return view('$viewDir.show', compact('$varName'));
        return response()->json(\$$varName);
    }

    public function edit(\$id)
    {
        \$$varName = $modelName::findOrFail(\$id);
        // return view('$viewDir.edit', compact('$varName'));
    }

    public function update(Request \$request, \$id)
    {
        \$validated = \$request->validate([
            // Add validation rules here
        ]);

        \$$varName = $modelName::findOrFail(\$id);
        \$${varName}->update(\$validated);
        
        // return redirect()->route('$viewDir.index')->with('success', 'Data updated successfully.');
        return response()->json(['message' => 'Updated successfully', 'data' => \$$varName]);
    }

    public function destroy(\$id)
    {
        \$$varName = $modelName::findOrFail(\$id);
        \$${varName}->delete();
        
        // return redirect()->route('$viewDir.index')->with('success', 'Data deleted successfully.');
        return response()->json(['message' => 'Deleted successfully']);
    }
}
EOT;

    file_put_contents(__DIR__ . '/app/Http/Controllers/' . $controller . '.php', $content);
}

echo "Controllers generated with basic CRUD structure.\n";
