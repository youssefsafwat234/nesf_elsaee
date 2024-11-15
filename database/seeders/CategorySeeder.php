<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'شقة',
            'فيلا',
            'دوبلكس',
            'بنتهاوس',
            'قصر',
            'استوديو',
            'عمارة سكنية',
            'محل تجاري',
            'مكتب',
            'مزرعة',
            'أرض',
            'تاون هاوس',
        ];
        foreach ($categories as $category) {
            \App\Models\Category::create(['name' => $category]);
        }
    }
}
