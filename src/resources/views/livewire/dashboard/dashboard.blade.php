<div>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="text-3xl font-semibold leading-tight text-gray-200">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="h-full pt-6 bg-sky-950">
        <div class="h-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="min-h-full shadow-xl sm:rounded-lg">
                {{-- Chart --}}
                <livewire:dashboard.chart />

                {{-- Histories --}}
                <livewire:dashboard.histories />
            </div>
        </div>
    </div>
</div>
