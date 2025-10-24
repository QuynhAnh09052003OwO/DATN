<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'N1',
                'is_active' => true,
            ],
            [
                'name' => 'N2',
                'is_active' => true,
            ],
            [
                'name' => 'N3',
                'is_active' => true,
            ],
            [
                'name' => 'N4',
                'is_active' => true,
            ],
            [
                'name' => 'N5',
                'is_active' => true,
            ],
            [
                'name' => 'Kaiwa',
                'is_active' => true,
            ],
            [
                'name' => 'JLPT',
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'is_active' => true,
            ],
            [
                'name' => 'Nattest',
                'is_active' => false,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}