<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'email' => 'noreply@prisma.care',
            'password' => Hash::make(str_random(20)),
            'first_name' => 'Prisma',
            'last_name' => 'System'
        ]);
    }
}
