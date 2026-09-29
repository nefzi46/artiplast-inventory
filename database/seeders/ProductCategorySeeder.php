<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
$categories = [
    'PVC',
    'PEHD (Polyéthylène haute densité)',
    'PEBD (Polyéthylène basse densité)',
    'PP (Polypropylène)',
    'PS (Polystyrène)',
    'PET',
    'ABS',
    'PA (Polyamide / Nylon)',
    'PC (Polycarbonate)',
    'PMMA (Plexiglas)',
    'Films et sachets',
    'Tubes et raccords',
    'Profilés et plaques',
    'Matières recyclées',
];

        foreach ($categories as $category) {
            ProductCategory::create([
                'category_name' => $category,
                'category_slug' => Str::slug($category),
            ]);
        }
    }
}
