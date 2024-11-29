<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'الرياض',
            'الدرعية',
            'الخرج',
            'المزاحمية',
            'حوطة بني تميم',
            'وادي الدواسر',
            'الأفلاج',
            'شقراء',
            'عفيف',
            'الدوادمي',
            'رماح',
            'ثادق',
            'الحريق',
            'السليل',
            'الغاط',
            'المجمعة',
            'ضرما',
            'القويعية',
            'حريملاء',
        ];
        foreach ($cities as $city) {
            \App\Models\City::create(['name' => $city]);
        }
    }
}
