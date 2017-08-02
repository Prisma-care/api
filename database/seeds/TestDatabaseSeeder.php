<?php

namespace Database\Seeds;

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
        $this->call(CategoryTableSeeder::class);
        $this->call(HeritageTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(UserTestTableSeeder::class);
        $this->call(PatientTestTableSeeder::class);
        $this->call(AlbumTestTableSeeder::class);
    }
}
