<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Task', 'Work', 'Study', 'Personal', 'Money', 'Sosial', 'Health', 'Hoby', 'Productivity'];

        foreach ($categories as $category) {
            Category::create([
                'categories_name' => $category
            ]);
        }
    }
}
