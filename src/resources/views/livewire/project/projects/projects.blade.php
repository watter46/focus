<div>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="text-3xl font-semibold leading-tight text-gray-200">
                {{ __('Project') }}
            </h2>    
        </div>
    </x-slot>

    <div class="relative h-full bg-sky-950">
        <div class="h-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- ToNewProjectButton --}}
            <div class="ml-auto bg-sky-950">
                <div class="flex items-center justify-end">
                    <button
                        type="button"
                        class="px-4 py-2 font-bold text-white border border-none rounded-xl bg-lime-600 hover:bg-lime-500"
                        wire:click="toNewProjectPage">
                        New Project
                    </button>
                </div>
            </div>
            
            <div class="min-h-full shadow-xl sm:rounded-lg">
                @if ($projects)
                    <div class="mt-4 bg-gray-700 border-2 border-gray-600 rounded-lg">
                        {{-- FilterBar --}}
                        <div class="flex items-center p-3 bg-gray-800 border-b-2 border-gray-600">
                            {{-- SortLabel --}}
                            <div class="relative px-2 ml-auto"
                                x-data="{ isOpen: false }"
                                @click.outside="isOpen = false">
                                
                                <button class="flex items-center"
                                    @click="isOpen = !isOpen">
                                    <p class="text-lg text-gray-400 pointer-events-none">Label</p>
                                    <x-icons.filled-down-triangle />
                                </button>
                        
                                <div
                                    x-cloak
                                    x-show="isOpen">
                                    <div class="absolute right-0 flex items-center cursor-pointer top-full">
                                        <div class="bg-gray-800 border border-gray-400 rounded-lg w-44">
                                            <p class="px-2 py-3 text-sm font-medium text-white pointer-events-none">Sort Label</p>
                                            @foreach ($form->LABELS as $LABEL)
                                                <div class="label-hover flex p-1.5 text-xs text-white border-t border-gray-700 hover:bg-sky-800"
                                                    wire:click="sortLabel('{{ $LABEL->get('text') }}')"
                                                    @click="isOpen = false">
                                                    @if (!$this->isSame($LABEL))
                                                        <span class="{{ $LABEL->get('class') }}"></span>
                                                        <p class="label-text">
                                                            {{ $LABEL->get('text') }}
                                                        </p>
                                                    @endif

                                                    @if ($this->isSame($LABEL))
                                                        <span class="{{ $LABEL->get('class') }}"></span>
                                                        <p class="label-text-selected">
                                                            {{ $LABEL->get('text') }}
                                                        </p>
                                                        <x-icons.selected-cross />
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            {{-- SortProgress --}}
                            <div class="relative flex items-center justify-end px-2"
                                x-data="{ isOpen: false }"
                                @click.outside="isOpen = false">
                        
                                <button class="flex items-center" @click="isOpen = !isOpen">
                                    <p class="text-lg text-gray-400 pointer-events-none">Progress</p>
                                    <x-icons.filled-down-triangle />
                                </button>
                                
                                <div
                                    x-cloak
                                    x-show="isOpen">
                                    <div class="absolute right-0 flex items-center cursor-pointer top-full">
                                        <div class="pb-3 bg-gray-800 border border-gray-400 rounded-lg w-36">
                                            <p class="px-2 py-3 text-sm font-medium text-white border-b border-gray-600">
                                                Sort Progress
                                            </p>
                                            
                                            <div class="flex px-3 py-1">
                                                <input class="w-4 h-4 m-auto cursor-pointer"
                                                    name="progressCompleted"
                                                    type="checkbox"
                                                    value="completed"
                                                    wire:change="sortProgress($event.target.value)"
                                                    wire:model="form.progress"
                                                    @change="isOpen = false">
                                                <p class="w-full ml-3 text-white" id="progressCompleted">
                                                    Completed
                                                </p>
                                            </div>
                        
                                            <div class="flex px-3 py-1">
                                                <input class="w-4 h-4 m-auto cursor-pointer"
                                                    name="progressAll"
                                                    type="checkbox"
                                                    value="all"
                                                    wire:change="sortProgress($event.target.value)"
                                                    wire:model="form.progress"
                                                    @change="isOpen = false">
                                                <p class="w-full ml-3 text-white" id="progressAll">
                                                    All
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ProjectList --}}
                        @foreach ($projects as $project)
                            <div class="flex items-center p-3 border-b-2 border-gray-600 hover:bg-gray-800 hover:underline hover:underline-offset-8 hover:decoration-gray-500">
                                {{-- ProjectName --}}
                                <div class="w-full">
                                    <ul id="projectName" class="text-gray-400 cursor-pointer"
                                        wire:click="toProjectDetailPage('{{ $project->id }}')">
                                        {{ $project->project_name }}
                                    </ul>
                                </div>

                                <div class="flex justify-between">
                                    @if ($project->is_complete)
                                        <x-icons.flag />
                                    @endif

                                    <p class="w-8 ml-auto mr-8 text-center border rounded-lg bg-zinc-600 border-zinc-400 text-zinc-200">
                                        1{{-- {{ $project->tasks_count }} --}}
                                    </p>

                                    <p class="w-28 {{ $this->labelData($project->label)->get('class') }}">
                                        {{ $this->labelData($project->label)->get('text') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="absolute inset-x-0 bottom-0 mb-3">        
        {{-- Pagination --}}
        {{ $projects->links('components.custom-pagination') }}
    </div>

    @push('projects-style')
        @vite(['resources/css/utils/label.css'])
    @endpush
</div>
