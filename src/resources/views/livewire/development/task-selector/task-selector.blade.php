<div x-data>
    @if (!$isStart)
        <div class="p-2 mx-3 border border-gray-600 rounded-lg" x-init="taskSelectorSortable()">
            <div class="grid h-full grid-cols-2 gap-2 p-1" style="min-height: 60vh;">
                {{-- InCompleteTasks --}}
                <div class="border-2 border-gray-600 rounded-lg">
                    <div class="px-2 py-1 mb-1 border-b-2 border-gray-600"
                        id="incompleteTasks-title">
                        <p class="text-2xl text-white">InComplete</p>
                    </div>
                
                    @if ($titles)
                        <div id="incompleteTasks">
                            @foreach ($titles as $title)
                                <div class="flex items-center h-14 mb-1.5 mx-2 px-5 text-white text-lg rounded bg-gray-700"
                                    data-id="{{ $title['id'] }}">
                                    <div class="pl-2" id="selected-task-title">
                                        {{ $title['title'] }}</div>
                                    <div
                                        class="px-3 py-1 ml-auto text-white bg-gray-600 rounded-lg">
                                        {{ $title['count'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                
                    @if (!$titles)
                        <div id="incompleteTasks"></div>
                    @endif
                </div>

                {{-- DevelopmentTasks --}}
                <div class="border-2 border-gray-600 rounded-lg">
                    <div class="px-2 py-1 mb-1 border-b-2 border-gray-600">
                        <p class="text-2xl text-white">Development</p>
                    </div>
                
                    <div id="developmentTasks"></div>
                </div>                
            </div>
        </div>
    @endif

    @if ($isStart)
        {{-- InProgressTasks --}}
        <livewire:development.task-selector.inprogress-tasks
            :$projectId
            :$developmentId />
    @endif

    @push('incompleteTasks-style')
        @vite(['resources/css/development/taskSelector/incompleteTasks.css'])
    @endpush

    @push('editor-script')
        @vite(['resources/js/project/editor.js'])
    @endpush

    @push('projectDetail-script')
        @vite(['resources/js/project/projectDetail.js'])
    @endpush

    @push('taskSelector-script')
        @vite(['resources/js/development/taskSelector/taskSelector.js'])
    @endpush
</div>
