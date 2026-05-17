<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestSeeder extends Seeder {
    public function run(): void {
        DB::table('guest')->insert([
            ['id' => 1, 'nik' => '3209072512850001', 'name' => 'Haji Ahmad Suryanto', 'gender' => 'MALE', 'address' => 'Jl. Merdeka No. 45, Banjarmasin', 'bloodType' => 'O', 'notes' => 'Tamu reguler, sering menggunakan layanan'],
            ['id' => 2, 'nik' => '3209151809870002', 'name' => 'Hajja Siti Nurhaliza', 'gender' => 'FEMALE', 'address' => 'Jl. Sultan Adam No. 12, Banjarmasin', 'bloodType' => 'AB', 'notes' => 'Tamu baru keluarga besar'],
            ['id' => 3, 'nik' => '3209089907920003', 'name' => 'Haji Bambang Irawan', 'gender' => 'MALE', 'address' => 'Jl. Hasanuddin No. 78, Banjarmasin', 'bloodType' => 'B', 'notes' => 'Tamu korporat dari PT Maju Jaya'],
        ]);
    }
}