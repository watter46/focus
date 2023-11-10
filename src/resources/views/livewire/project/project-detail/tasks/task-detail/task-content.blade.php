<div
    id="task"
    class="p-2 mt-2"
    wire:ignore
    x-data
    x-init="sortable($el)">
    @foreach ($tasks as $task)
        @if ($task->has('ul'))
            <ul id="sortable">
                @foreach ($task->get('ul') as $command)
                    <li x-id="['task-checkbox']">
                        <div class="flex items-center">
                            <span class="px-3 py-2 opacity-0 cursor-pointer hover:opacity-100 handle">:::</span>
                            
                            <div class="flex items-center w-full" x-cloak>
                                <input
                                    type="checkbox"
                                    :id="$id('task-checkbox')"
                                    class="mr-2"
                                    @checked($command->get('isChecked'))
                                    @click="check($el)">
                                
                                <span id="taskText">{{ $command->get('command') }}</span>
                            </div>
                        </div>

                        @if ($command->has('comments'))
                            @foreach ($command->get('comments') as $comment)
                                <div class="ml-14">
                                    <p id="taskText" class="break-words">
                                        {{ $comment }}
                                    </p>
                                </div>
                            @endforeach
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($task->has('newline'))
            <br id="taskText">
        @endif

        @if ($task->has('comment'))
            <p id="taskText" class="break-words">{{ $task->get('comment') }}</p>
        @endif
    @endforeach
</div>
