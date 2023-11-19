<div class="relative flex justify-between mt-3">
    <p class="text-xl text-white">Task Total: {{ $taskCount }}</p>

    <button class="relative flex justify-end w-48 px-2 py-1 text-gray-400 bg-gray-600 rounded-lg hover:bg-gray-500"
        @click="isOpen = !isOpen"
        @close-change-task.window="isOpen = false">
        <p class="flex justify-center w-full">Change</p>
        <x-icons.change-task />
    </button>

    <div 
        x-show="isOpen"
        class="absolute right-0 z-10 w-1/2 p-4 bg-gray-800 border border-gray-600 rounded-lg top-full"
        style="min-height: 300px;"
        @click.outside="isOpen = false">
        <div class="relative" style="min-height: 300px;">
            <p class="p-2 text-xl text-white border-b-2 border-gray-400">Change Task</p>
 
            <div class="mt-5">
                @foreach ($remainingTasks as $task)
                    <div class="flex items-center">
                        <input type="checkbox"
                            id="changeTask-checkbox"
                            class="mx-5"
                            wire:change="change('{{ $task->id }}', $event.target.checked)">
                        <p class="text-xl text-white">{{ $task->name }}</p>
                    </div>
                @endforeach
            </div>

            <div class="absolute bottom-0 right-0">
                <button class="px-8 py-1 text-white rounded-lg bg-sky-700 hover:bg-sky-600"
                    wire:click="save"
                >Save</button>
            </div>
        </div>
    </div>
</div>
