<?php

use App\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientTestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $patient = Patient::create([
            'first_name' => 'Patient',
            'last_name' => 'Testing'
        ]);
    }
}

