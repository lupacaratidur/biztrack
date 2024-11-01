<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'      => 'Administrator',
            'email'     => 'administrator@gmail.com',
            'password'  =>  bcrypt('1234'),
            'role_id'   => 1,
            'cabang_id' => 1
        ]);
        User::create([
            'name'      => 'Kepala Restoran',
            'email'     => 'kepalarestoran@gmail.com',
            'password'  =>  bcrypt('1234'),
            'role_id'   => 2,
            'cabang_id' => 1
        ]);
        User::create([
            'name'      => 'mandono',
            'email'     => 'mandono@gmail.com',
            'password'  =>  bcrypt('1234'),
            'role_id'   => 4,
            'cabang_id' => 1
        ]);
        User::create([
            'name'      => 'Mujiyono',
            'email'     => 'mujiyono@gmail.com',
            'password'  =>  bcrypt('1234'),
            'role_id'   => 3,
            'cabang_id' => 1
        ]);
        User::create([
            'name'      => 'Abdul',
            'email'     => 'abdul@gmail.com',
            'password'  =>  bcrypt('1234'),
            'role_id'   => 4,
            'cabang_id' => 2
        ]);
        User::create([
            'name'      => 'Agung',
            'email'     => 'agung@gmail.com',
            'password'  =>  bcrypt('1234'),
            'role_id'   => 3,
            'cabang_id' => 2
        ]);

        Cabang::create([
            'cabang'    => 'Pusat',
            'alamat'    => 'Cabang Pusat'
        ]);
        Cabang::create([
            'cabang'    => 'Cabang 1',
            'alamat'    => 'Cabang 1'
        ]);

        Role::create([
            'role'      => 'administrator',
            'deskripsi' => 'Memiliki semua hak akses'
        ]);

        Role::create([
            'role'      => 'kepala restoran',
            'deskripsi' => 'Memiliki hak akses pada laporan per cabang maupun semua'
        ]);

        Role::create([
            'role'      => 'kasir',
            'deskripsi' => 'Memiliki hak akses pada menu kasir'
        ]);

        Role::create([
            'role'      => 'admin',
            'deskripsi' => 'Memiliki hak akses manajemen produk dan laporan cabang'
        ]);
    }
}
