<?php

$migrationsDir = __DIR__ . '/database/migrations';
$modelsDir = __DIR__ . '/app/Models';
$controllersDir = __DIR__ . '/app/Http/Controllers';
$seedersDir = __DIR__ . '/database/seeders';

$tables = [
    'activity_log' => ['model' => 'ActivityLog', 'controller' => 'ActivityLogController', 'seeder' => 'ActivityLogSeeder'],
    'detail_peminjaman_sarana' => ['model' => 'DetailPeminjamanSarana', 'controller' => 'DetailPeminjamanSaranaController', 'seeder' => 'DetailPeminjamanSaranaSeeder'],
    'gedung' => ['model' => 'Gedung', 'controller' => 'GedungController', 'seeder' => 'GedungSeeder'],
    'guest' => ['model' => 'Guest', 'controller' => 'GuestController', 'seeder' => 'GuestSeeder'],
    'invoice' => ['model' => 'Invoice', 'controller' => 'InvoiceController', 'seeder' => 'InvoiceSeeder'],
    'media_file' => ['model' => 'MediaFile', 'controller' => 'MediaFileController', 'seeder' => 'MediaFileSeeder'],
    'paket_ruangan' => ['model' => 'PaketRuangan', 'controller' => 'PaketRuanganController', 'seeder' => 'PaketRuanganSeeder'],
    'peminjaman_transaksi' => ['model' => 'PeminjamanTransaksi', 'controller' => 'PeminjamanTransaksiController', 'seeder' => 'PeminjamanTransaksiSeeder'],
    'ruangan' => ['model' => 'Ruangan', 'controller' => 'RuanganController', 'seeder' => 'RuanganSeeder'],
    'sarana' => ['model' => 'Sarana', 'controller' => 'SaranaController', 'seeder' => 'SaranaSeeder'],
];

$datePrefix = date('Y_m_d_His');
$i = 0;
foreach ($tables as $table => $info) {
    $i++;
    $migFile = $migrationsDir . '/' . date('Y_m_d', strtotime("+$i seconds")) . '_000000_create_' . $table . 's_table.php';
    if (!file_exists($migFile)) {
        file_put_contents($migFile, "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n        Schema::create('{$table}', function (Blueprint \$table) {\n            \$table->id();\n            \$table->timestamps();\n        });\n    }\n\n    public function down(): void\n    {\n        Schema::dropIfExists('{$table}');\n    }\n};\n");
    }

    $modelFile = $modelsDir . '/' . $info['model'] . '.php';
    if (!file_exists($modelFile)) {
        file_put_contents($modelFile, "<?php\n\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Factories\HasFactory;\nuse Illuminate\Database\Eloquent\Model;\n\nclass {$info['model']} extends Model\n{\n    use HasFactory;\n    protected \$table = '{$table}';\n    protected \$guarded = [];\n}\n");
    }

    $controllerFile = $controllersDir . '/' . $info['controller'] . '.php';
    if (!file_exists($controllerFile)) {
        file_put_contents($controllerFile, "<?php\n\nnamespace App\Http\Controllers;\n\nuse App\Models\\{$info['model']};\nuse Illuminate\Http\Request;\n\nclass {$info['controller']} extends Controller\n{\n    public function index()\n    {\n        //\n    }\n}\n");
    }

    $seederFile = $seedersDir . '/' . $info['seeder'] . '.php';
    if (!file_exists($seederFile)) {
        file_put_contents($seederFile, "<?php\n\nnamespace Database\Seeders;\n\nuse Illuminate\Database\Console\Seeds\WithoutModelEvents;\nuse Illuminate\Database\Seeder;\n\nclass {$info['seeder']} extends Seeder\n{\n    public function run(): void\n    {\n        //\n    }\n}\n");
    }
}
echo "Done\n";
