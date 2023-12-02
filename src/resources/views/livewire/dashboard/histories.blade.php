<div class="px-5 py-3 mt-3 bg-gray-800 rounded-lg">
    <p class="text-3xl text-gray-300">Histories</p>

    <div class="py-3 mt-2 border-t-4 border-gray-600">
        <div class="grid grid-cols-2 gap-4">
            @foreach ($histories as $history)
                <div class="relative p-2 text-gray-300 border border-gray-600 rounded-lg"
                    x-data="{ open: false }"
                    :class="{ 'z-10': open, 'h-28': !open }">
                    <div class="flex justify-between mb-3">
                        <p class="flex items-center text-2xl">
                            {{ $history->project_name }}</p>
                        </p>
                    </div>

                    <div class="h-12 px-5">
                        <div class="flex items-center justify-between h-full">
                            <p class="{{ $this->label($history->label)->get('class') }}">
                                {{ $this->label($history->label)->get('text') }}
                            <p class="mr-10 text-3xl text-sky-600">{{ $history->toMin() }} m</p>
                        </div> 
                    </div>

                    <template x-if="!open">
                        <div class="absolute inset-x-0 bottom-0 flex justify-center"
                            @click="open = true">
                            <x-icons.arrow-down />
                        </div>
                    </template>

                    <div x-show="open" class="flex">
                        <div class="w-1/2 h-full">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="px-2 py-2 text-xl">Start:</td>
                                        <td class="px-2 py-2">{{ $history->started_at }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-2 text-xl">Finish:</td>
                                        <td class="px-2 py-2">{{ $history->finished_at }}</td>
                                    </tr>
                                </tbody>
                              </table>
                        </div>

                        <div class="w-1/2 py-2">
                            @if($history->completed_task_list)
                                <p class="ml-3 text-xl">CompleteTasks</p>
                                @foreach($history->completed_task_list as $name)
                                    <p class="ml-12">- {{ $name }}</p>
                                @endforeach
                            @endif
                        </div>
                        
                        <div class="absolute inset-x-0 bottom-0 flex justify-center"
                            @click="open = false">
                            <x-icons.arrow-up />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        {{ $histories->links('components.custom-pagination') }}
    </div>

    @push('histories-style')
        @vite(['resources/css/utils/label.css'])
    @endpush
</div>
