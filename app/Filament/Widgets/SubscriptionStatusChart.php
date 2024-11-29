<?php

namespace App\Filament\Widgets;

use Illuminate\Contracts\View\View;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Carbon\Carbon;
use DB;

class SubscriptionStatusChart extends ApexChartWidget
{
    /**
     * Chart Id
     */
    protected static ?string $chartId = 'SubscriptionStatusChart';

    /**
     * Widget Title
     */
    protected static ?string $heading = 'الاشتراكات - الإحصائيات';

    /**
     * Sort
     */
    protected static ?int $sort = 1;

    /**
     * Widget content height
     */
    protected static ?int $contentHeight = 215;

    /**
     * Fetch dynamic data for subscriptions
     */
    protected function fetchData(): array
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek()->toDateTimeString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateTimeString();
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek()->toDateTimeString();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek()->toDateTimeString();
        $startOfMonth = Carbon::now()->startOfMonth()->toDateTimeString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateTimeString();


        $thisWeek = DB::table('subscription_users')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();

        $lastWeek = DB::table('subscription_users')
            ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->count();

        $thisMonth = DB::table('subscription_users')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        return [
            'thisWeek' => $thisWeek,
            'lastWeek' => $lastWeek,
            'thisMonth' => $thisMonth,
        ];
    }

    /**
     * Widget Footer
     */
    protected function getFooter(): string|View
    {

        $data = $this->fetchData();

        return view('charts.order-status.footer', ['data' => $data]);
    }

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     */
    protected function getOptions(): array
    {
        $data = $this->fetchData();

        $slope = ($data['lastWeek'] == 0) ? 100 : ($data['thisWeek'] / $data['lastWeek']) * 100;

        return [
            'chart' => [
                'type' => 'radialBar',
                'height' => 280,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [$slope],
            'labels' => ['هذا الأسبوع', 'الأسبوع السابق', 'هذا الشهر'],
            'plotOptions' => [
                'radialBar' => [
                    'startAngle' => -140,
                    'endAngle' => 130,
                    'hollow' => [
                        'size' => '60%',
                        'background' => 'transparent',
                    ],
                    'track' => [
                        'background' => 'transparent',
                        'strokeWidth' => '100%',
                    ],
                    'dataLabels' => [
                        'show' => true,
                        'name' => [
                            'show' => true,
                            'offsetY' => -10,
                            'fontWeight' => 600,
                            'fontFamily' => 'inherit',
                        ],
                        'value' => [
                            'show' => true,
                            'fontWeight' => 600,
                            'fontSize' => '24px',
                            'fontFamily' => 'inherit',
                        ],
                    ],
                ],
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shade' => 'dark',
                    'type' => 'horizontal',
                    'shadeIntensity' => 0.5,
                    'gradientToColors' => ['#f59e0b'],
                    'inverseColors' => true,
                    'opacityFrom' => 1,
                    'opacityTo' => 0.6,
                    'stops' => [30, 70, 100],
                ],
            ],
            'stroke' => [
                'dashArray' => 10,
            ],
            'colors' => ['#16a34a', '#f59e0b', '#1d4ed8'],
        ];
    }
}
