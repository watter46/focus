<div class="px-12">
    {{-- ProjectName --}}
    <livewire:project.project-detail.project-name.project-name :$projectId />

    <div class="flex justify-center">
        {{-- Tasks --}}
        <livewire:project.project-detail.tasks.tasks :$projectId />

        <div class="w-3/12">
            {{-- Label --}}
            <livewire:utils.label-selector
                :$projectId
                title="SelectLabel" />

            {{-- ToDevelopmentPageButton --}}
            <div class="flex items-center justify-center w-full mt-10">
                <button class="flex items-center justify-center h-40 bg-gray-400 w-60 rounded-xl hover:bg-gray-300 hover:cursor-pointer"
                    wire:click="toDevelopmentPage">
                    <x-icons.timer />
                    <p class="text-3xl text-gray-700">Develop</p>
                </button>
            </div>          
        </div>
    </div>

    @push('projectDetail-script')
        @vite(['resources/js/project/projectDetail.js'])
    @endpush

    @push('editor-script')
        @vite(['resources/js/project/editor.js'])
    @endpush
</div>