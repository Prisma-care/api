<?php

use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(TestUsersTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(HeritageTableSeeder::class);
    }
}
