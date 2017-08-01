<?php

use App\Album;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AlbumTestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $album = Album::create([
            'title' => 'Taken',
            'description' => null,
            'patient_id' => 1
        ]);
    }
}
