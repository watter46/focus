<div>
    <div x-data="{ data: @entangle('data') }" x-init="dashboardSetup(data)"></div>
    
    <div class="flex px-5 py-3 bg-gray-800 rounded-lg">
        <div class="w-3/4">
            <canvas id="myChart"></canvas>
        </div>
        
        <div class="w-1/4 text-gray-400">
            <p class="mb-5 text-3xl">Total: {{ $totalTime }} (h)</p>
            <p class="text-xl">WeeklyAvg: {{ $weeklyAvg }} (m)</p>
        </div>
    </div>

    @push('chart-script')
        @vite(['resources/js/dashboard/chart.js'])
    @endpush
</div>