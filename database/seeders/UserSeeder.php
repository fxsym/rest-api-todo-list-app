<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Fatih',
                'username' => 'fxsym',
                'email' => 'fatih@mail.com',
                'password' => encrypt('fxsym'),
            ],
            [
                'name' => 'Oca',
                'username' => 'meilika',
                'email' => 'oca@mail.com',
                'password' => encrypt('meilika'),
            ],
            [
                'name' => 'Budi',
                'username' => 'santoso',
                'email' => 'budi@mail.com',
                'password' => Hash::make('santoso'),
            ],
        ];
        User::insert($users);
    }
}
