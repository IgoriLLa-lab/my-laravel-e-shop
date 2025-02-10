<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryImages = [
            'Мобильные Телефоны' => 'iphone.jpg',
            'Портативная техника' => 'jbl-portable.jpg',
            'Бытовая техника' => 'bort-tech.jpg',
            'Игровые консоли' => 'xbox.jpeg',
            'Телевизоры' => 'smart-tv.jpg',
        ];

        $categories = DB::table('categories')->get();

        foreach ($categories as $category) {
            $image = $categoryImages[$category->name] ?? null;

            for ($i = 1; $i <= 40; $i++) {
                DB::table('products')->insert([
                    'category_id' => $category->id,
                    'name' => $category->name . ' Product ' . $i,
                    'code' => strtolower($category->code) . '-' . $i,
                    'description' => 'Описание продукта ' . $i . ' из категории ' . $category->name,
                    'image' => $image,
                    'price' => rand(1000, 50000) / 100,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'count' => rand(0, 100)
                ]);
            }
        }
    }
}
