<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::factory()
            ->count(4)
            ->sequence(
                ['name' => 'Fruits'],
                ['name' => 'Vegetables'],
                ['name' => 'Diary, Eggs & Meals'],
                ['name' => 'Drinks']
            )
            ->create();
    }
}
