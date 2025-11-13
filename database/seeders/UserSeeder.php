<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'is_operator' => false,
                'is_mahasiswa' => false,
                'is_tata_usaha' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Operator',
                'email' => 'operator@gmail.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_operator' => true,
                'is_mahasiswa' => false,
                'is_tata_usaha' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mahasiswa',
                'email' => 'mahasiswa@gmail.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_operator' => false,
                'is_mahasiswa' => true,
                'is_tata_usaha' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tata Usaha',
                'email' => 'tatausaha@gmail.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_operator' => false,
                'is_mahasiswa' => false,
                'is_tata_usaha' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
       
        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}
