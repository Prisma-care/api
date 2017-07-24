<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
        	'name' => '40s'
        	],
        	[
        	'name' => '50s'
        	],
        	[
        	'name' => '60s'
        	],
        	[
        	'name' => 'Feesten'
        	],
        	[
        	'name' => 'Gebouwen'
        	],
        	[
        	'name' => 'Koningen'
        	],
        	[
        	'name' => 'Personen'
        	],
        	[
        	'name' => 'Sport'
        	],
        	[
        	'name' => 'Roeselare'
        	],
            [
            'name' => 'Voeding'
            ]
        ];

        foreach($categories as $category){
        	Category::create($category);
        }
    }
}
