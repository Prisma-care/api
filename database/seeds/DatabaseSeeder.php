<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use Production\UsersTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoryTableSeeder::class);
        $this->call(HeritageTableSeeder::class);
        $this->call(UserTableSeeder::class);
    }
}
