<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-400">
            {{ __('InDevelopments') }}
        </h2>
    </x-slot>
    
    <div class="relative pt-6 h-96">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="shadow-xl sm:rounded-lg">
                @if ($projects)
                    <div class="mt-4 bg-gray-700 border-2 border-gray-600 rounded-lg">
                        @foreach ($projects->getCollection() as $project)
                            <div class="flex items-center p-3 border-b-2 border-gray-600 hover:bg-gray-800 hover:underline hover:underline-offset-8 hover:decoration-gray-500">
                                <div class="w-full text-gray-400 cursor-pointer"
                                    wire:click="toDevelopment('{{ $project->id }}')">
                                    {{ $project->project_name }}
                                </div>

                                <div class="flex justify-between">
                                    @if ($project->is_complete)
                                        <x-icons.flag />
                                    @endif

                                    <p class="w-8 ml-auto mr-8 text-center border rounded-lg bg-zinc-600 border-zinc-400 text-zinc-200">
                                        {{ $project->tasks_count }}
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

        <div class="absolute inset-x-0 bottom-0">
            {{-- Pagination --}}
            {{ $projects->links('components.custom-pagination') }}
        </div>
    </div>

    

    @push('projects-style')
        @vite(['resources/css/utils/label.css'])
    @endpush    
</div>
