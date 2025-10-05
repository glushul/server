<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Глущана Ульющенко',
            'email' => 'ulyana.glushchenko2031@mail.ru',
            'password' => Hash::make('123456'),
            'role_id' => 1
        ]);
    }
}
