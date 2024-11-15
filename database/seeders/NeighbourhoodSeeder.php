<?php

namespace Database\Seeders;

use App\Models\Neighbourhood;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NeighbourhoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $neighborhoods = [
            1 => ['حي الشفاء', 'حي النسيم', 'حي الملز'],
            2 => ['حي الدرعية', 'حي الخالدية'],
            3 => ['حي الخرج'],
            4 => ['حي المزاحمية', 'حي العزيزية'],

        ];

        // Loop through each city and insert neighborhoods
        foreach ($neighborhoods as $cityId => $neighborhoodList) {
            foreach ($neighborhoodList as $neighborhood) {
                Neighbourhood::create([
                    'city_id' => $cityId,
                    'name' => $neighborhood,
                ]);
            }
        }
    }
}
