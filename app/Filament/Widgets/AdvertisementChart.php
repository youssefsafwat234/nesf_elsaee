<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use App\Models\Advertisement; // Make sure to import the Advertisement model

class AdvertisementChart extends ApexChartWidget
{
    /**
     * Chart Id
     */
    protected static ?string $chartId = 'advertisementsChart';

    /**
     * Widget Title
     */
    protected static ?string $heading = 'الإعلانات خلال الشهر';

    /**
     * Sort
     */
    protected static ?int $sort = 3;

    /**
     * Widget content height
     */
    protected static ?int $contentHeight = 260;

    /**
     * Filter Form
     */
    protected function getFormSchema(): array
    {
        return [
            Radio::make('advertisementsChartType')
                ->default('bar')
                ->options([
                    'line' => 'خط',
                    'bar' => 'عمود',
                    'area' => 'منطقة',
                ])
                ->inline(true)
                ->label('النوع'),

            Grid::make()
                ->schema([
                    Toggle::make('advertisementsChartMarkers')
                        ->default(false)
                        ->label('العلامات'),

                    Toggle::make('advertisementsChartGrid')
                        ->default(false)
                        ->label('الشبكة'),
                ]),

            TextInput::make('advertisementsChartAnnotations')
                ->required()
                ->numeric()
                ->default(5000)
                ->label('التعليقات'),
        ];
    }

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     */
    protected function getOptions(): array
    {
        $filters = $this->filterFormData;

        // Collect advertisement counts for each month
        $monthlyAdsData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyAdsData[] = Advertisement::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }

        return [
            'chart' => [
                'type' => $filters['advertisementsChartType'],
                'height' => 250,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'الإعلانات لكل شهر',
                    'data' => $monthlyAdsData,
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 2,
                ],
            ],
            'xaxis' => [
                'categories' => [
                    'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
                    'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
                ],
                'labels' => [
                    'style' => [
                        'fontWeight' => 400,
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontWeight' => 400,
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shade' => 'dark',
                    'type' => 'vertical',
                    'shadeIntensity' => 0.5,
                    'gradientToColors' => ['#fbbf24'],
                    'inverseColors' => true,
                    'opacityFrom' => 1,
                    'opacityTo' => 1,
                    'stops' => [0, 100],
                ],
            ],

            'dataLabels' => [
                'enabled' => false,
            ],
            'grid' => [
                'show' => $filters['advertisementsChartGrid'],
            ],
            'markers' => [
                'size' => $filters['advertisementsChartMarkers'] ? 3 : 0,
            ],
            'tooltip' => [
                'enabled' => true,
            ],
            'stroke' => [
                'width' => $filters['advertisementsChartType'] === 'line' ? 4 : 0,
            ],
            'colors' => ['#f59e0b'],
            'annotations' => [
                'yaxis' => [
                    [
                        'y' => $filters['advertisementsChartAnnotations'],
                        'borderColor' => '#ef4444',
                        'borderWidth' => 1,
                        'label' => [
                            'borderColor' => '#ef4444',
                            'style' => [
                                'color' => '#fffbeb',
                                'background' => '#ef4444',
                            ],
                            'text' => 'تعليق: ' . $filters['advertisementsChartAnnotations'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
