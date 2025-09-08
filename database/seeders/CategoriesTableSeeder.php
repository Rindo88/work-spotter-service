<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Makanan',
            'Minuman',
            'Jasa',
            'Pakaian',
            'Elektronik',
            'Kesehatan',
            'Otomotif',
            'Kerajinan Tangan',
            'Peralatan Rumah Tangga',
            'Perlengkapan Bayi',
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
                'description' => 'Kategori untuk produk ' . strtolower($category),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}