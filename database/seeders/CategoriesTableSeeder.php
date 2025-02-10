<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Мобильные Телефоны', 'code' => 'mobile', 'description' => 'Различные модели мобильных телефонов.', 'image' => null],
            ['name' => 'Портативная техника', 'code' => 'portable', 'description' => 'Портативные устройства и аксессуары.', 'image' => null],
            ['name' => 'Бытовая техника', 'code' => 'home-appliances', 'description' => 'Техника для дома.', 'image' => null],
            ['name' => 'Игровые консоли', 'code' => 'consoles', 'description' => 'Консоли для игр.', 'image' => null],
            ['name' => 'Телевизоры', 'code' => 'tvs', 'description' => 'Широкий выбор телевизоров.', 'image' => null],
        ];

        DB::table('categories')->insert($categories);
    }
}
