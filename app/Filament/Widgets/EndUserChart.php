<?php

namespace App\Filament\Widgets;

use App\Enums\AccountTypeEnum;
use App\Models\User;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class EndUserChart extends ApexChartWidget
{
    /**
     * Chart Id
     */
    protected static ?string $chartId = 'customersChart';

    /**
     * Widget Title
     */
    protected static ?string $heading = 'إجمالي العملاء خلال آخر 7 أيام';

    /**
     * Sort
     */
    protected static ?int $sort = 4;

    /**
     * Widget content height
     */
    protected static ?int $contentHeight = 270;

    /**
     * Chart options (series, labels, types, size, animations...).
     */
    protected function getOptions(): array
    {
        $dates = [];
        $dailyData = [];

        // Get customer counts for the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            // Set the locale to Arabic
            $date->locale('ar'); // Use Arabic locale

            // Format the day in Arabic
            $dates[] = $date->translatedFormat('l'); // 'd' will return the day of the month

            $dailyData[] = User::where('accountType', AccountTypeEnum::ENDUSER_ACCOUNT->value)
                ->whereDate('created_at', $date->toDateString())
                ->count();
        }


        return [
            'chart' => [
                'type' => 'line',
                'height' => 250,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'العملاء',
                    'data' => $dailyData,
                ],
            ],
            'xaxis' => [
                'categories' => $dates,
                'labels' => [
                    'style' => [
                        'fontWeight' => 400,
                        'fontFamily' => 'inherit',
                        'fontSize' => '12px',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontWeight' => 400,
                        'fontFamily' => 'inherit',
                        'fontSize' => '12px',
                    ],
                ],
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shade' => 'dark',
                    'type' => 'horizontal',
                    'shadeIntensity' => 1,
                    'gradientToColors' => ['#ea580c'],
                    'inverseColors' => true,
                    'opacityFrom' => 1,
                    'opacityTo' => 1,
                    'stops' => [0, 100, 100, 100],
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'grid' => [
                'show' => true,
                'borderColor' => '#f1f1f1',
            ],
            'markers' => [
                'size' => 4,
            ],
            'tooltip' => [
                'enabled' => true,
                'theme' => 'dark',
                'x' => [
                    'formatter' => 'function(value) { return "التاريخ: " + value; }',
                ],
                'y' => [
                    'formatter' => 'function(value) { return value + " عميل"; }',
                ],
            ],
            'stroke' => [
                'width' => 3,
            ],
            'colors' => ['#f59e0b'],
        ];
    }
}
