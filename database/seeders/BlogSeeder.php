<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
         foreach (range(1, 10) as $index) {
            Blog::create([
                'title' => $faker->paragraph,
                'description' => $faker->paragraph,
            ]);
         }
    }
}
