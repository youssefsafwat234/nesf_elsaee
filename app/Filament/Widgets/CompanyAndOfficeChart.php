<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Enums\AccountTypeEnum;

class CompanyAndOfficeChart extends ApexChartWidget
{
    /**
     * Chart Id
     */
    protected static ?string $chartId = 'companyOfficeChart';

    /**
     * Widget Title
     */
    protected static ?string $heading = 'الشركات والمكاتب (آخر 7 أيام)';

    /**
     * Sort
     */
    protected static ?int $sort = 2;

    /**
     * Widget content height
     */
    protected static ?int $contentHeight = 275;

    /**
     * Get chart options dynamically
     */
    protected function getOptions(): array
    {
        // Initialize data for the last 7 days
        $dates = [];
        $companiesData = [];
        $officesData = [];

        // Loop through the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);

            $date->locale('ar');


            $dates[] = $date->translatedFormat('l');

            // Query the count of companies and offices for this day
            $companiesCount = User::where('accountType', AccountTypeEnum::COMPANY_ACCOUNT->value)
                ->whereDate('created_at', $date)
                ->count();

            $officesCount = User::where('accountType', AccountTypeEnum::OFFICE_ACCOUNT->value)
                ->whereDate('created_at', $date)
                ->count();

            $companiesData[] = $companiesCount;
            $officesData[] = $officesCount;
        }

        // Configure the chart options
        return [
            'chart' => [
                'type' => 'bar',
                'height' => 260,
                'parentHeightOffset' => 2,
                'stacked' => true,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'الشركات',
                    'data' => $companiesData,
                ],
                [
                    'name' => 'المكاتب',
                    'data' => $officesData,
                ],
            ],
            'xaxis' => [
                'categories' => $dates,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'fontSize' => '12px',
                        'align' => 'center',
                    ],
                ],


            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'fontSize' => '12px',

                    ],
                ]
            ],
            'colors' => ['#d97706', '#c2410c'], // Green for Companies, Blue for Offices
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                    'columnWidth' => '50%',
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'legend' => [
                'show' => false,
                'horizontalAlign' => 'center',
                'position' => 'top',
                'fontFamily' => 'inherit',
                'labels' => [
                    'useSeriesColors' => true,
                ],
            ],
            'grid' => [
                'borderColor' => '#f1f1f1',
            ],
        ];
    }
}
