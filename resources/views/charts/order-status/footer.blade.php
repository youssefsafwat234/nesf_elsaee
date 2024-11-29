<div class="flex items-center justify-between mt-4 text-center">
    <div>
        <h1>هذا الأسبوع</h1>
        <div class="text-3xl">{{ $data['thisWeek'] }}</div>
    </div>

    <div>
        <h1>الأسبوع السابق</h1>
        <div class="text-3xl">{{ $data['lastWeek'] }}</div>
    </div>
    <div>
        <h1>هذا الشهر</h1>
        <div class="text-3xl">{{ $data['thisMonth'] }}</div>
    </div>
</div>