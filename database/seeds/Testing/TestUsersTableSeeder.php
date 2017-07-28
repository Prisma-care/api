<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'email' => 'testing@prisma.care',
            'password' => Hash::make('testing@prisma.care'),
            'first_name' => 'User',
            'last_name' => 'Test'
        ]);
    }
}

