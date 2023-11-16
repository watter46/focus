<div class="p-2">
    {{-- Timer --}}
    <livewire:development.timer.timer
        :$projectId
        :defaultTime="$development->default_time"
        :remainingTime="$development->remaining_time"
        :isStart="$development->is_start" />

    {{-- TaskSelector --}}
    <livewire:development.task-selector.task-selector
        :$projectId
        :developmentId="$development->id"
        :isStart="$development->is_start"
        :wire:key="$development->is_start" />

    {{-- BreakTime --}}
    <livewire:development.break-time.break-time
        :$projectId />

    @push('development-script')
        @vite(['resources/js/development/development.js'])
    @endpush
</div>