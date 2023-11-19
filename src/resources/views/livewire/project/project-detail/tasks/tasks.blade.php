<div class="w-8/12 pr-5">
    {{-- IncompleteTasksCount --}}
    <div
        class="flex"
        x-data="{
            isShowAll: @entangle('isShowAll')
        }">
        <p class="pt-1 mr-5 text-lg text-white" title="残りタスク数: {{ $project->incomplete_tasks_count }}">
            {{ $project->incomplete_tasks_count }} / {{ $project->tasks_count }}
        </p>

        {{-- InCompleteButton --}}
        <button
            x-show="isShowAll"
            x-cloak
            wire:click="$dispatch('fetch-project-incomplete-tasks', { projectId: '{{ $projectId }}' })">
            <x-icons.eye />
        </button>

        {{-- showAllButton --}}
        <button
            x-show="!isShowAll"
            x-cloak
            wire:click="$dispatch('fetch-project-tasks', { projectId: '{{ $projectId }}' })">
            <x-icons.stripe-on-eye />
        </button>
    </div>

    @foreach ($tasks as $task)
        <livewire:project.project-detail.tasks.task-detail.task-detail
            :$task
            :isComplete="$task->is_complete"
            :key="$refresh" />
    @endforeach

    <div class="w-full mt-3 border-b-2 border-gray-400"></div>

    {{-- TaskCreator --}}
    <div class="mt-3"
        x-data="{
            editor: new Editor(),
            resize(el) {
                el.setAttribute('style', `height: auto;`),
                el.setAttribute('style', `height: ${el.scrollHeight}px;`)
            },
            // Alpinejsでshift + altのkeydownメソッドがなぜか使えないためjavascriptで書く
            prependCheckbox(e) {
                if (!e.shiftKey) return;
                if (!e.altKey)   return;
                
                this.editor.prepend(e.target);
            },
            prependOrNone(id) {
                if (id !== 'create') return;
    
                this.editor.prepend($refs.newContent);
            },
            add() {
                $wire.set('content', $refs.newContent.value);
                $wire.add();
            }
        }">
    
        {{-- PrependCheckbox --}}
        <div class="flex justify-end w-full border border-gray-600 rounded-t-lg dark:bg-gray-700">
            <button
                class="p-2 mx-2 text-gray-500 rounded cursor-pointer parent-checkbox-btn hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600"
                type="button"
                title="shift + alt"
                @click="$dispatch('checkbox', 'create')">
                <x-icons.add-task />
            </button>
        </div>
        
        {{-- TaskName --}}
        <div x-id="['create-taskName-input']">
            <div class="bg-gray-800" style="height: 18px;">
                @error('name')
                    <span class="px-2 text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <label :for="$id('create-taskName-input')"></label>
            <input
                :id="$id('create-taskName-input')"
                class="w-full p-2 border-0 border-b-2 border-gray-300 appearance-none placeholder:text-sm bg-gray-50 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                autocomplete="off"
                placeholder="TaskName"
                wire:model="name"
                @keydown.ctrl.enter.prevent="add" />
        </div>
    
        {{-- Content --}}
        <div class="rounded-b-lg" x-id="['create-content-input']">
            <div class="bg-gray-800 " style="height: 18px;">
                @error('content')
                    <span class="px-2 text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <label class="sr-only" :for="$id('create-content-input')"></label>
            <textarea
                :id="$id('create-content-input')" 
                class="block w-full p-2 text-sm bg-white border-0 resize-none focus:outline-none dark:bg-gray-800 focus:ring-0 dark:text-white placeholder:text-sm"
                rows="8"
                placeholder="Write an task..."
                x-ref="newContent"
                wire:model="content"
                @input="resize($el)"
                @checkbox.window="prependOrNone($event.detail)"
                @keydown="prependCheckbox($event)"
                @keydown.ctrl.enter.prevent="add">
            </textarea>
        </div>
    
        <div class="flex justify-end mt-2">
            {{-- AddButton --}}
            <button
                class="inline-flex items-center px-5 py-2 font-medium text-center text-white rounded-lg bg-lime-600 focus:ring-4 dark:focus:ring-lime-500 hover:bg-lime-500"
                title="control + enter"
                @click="add">
                Add
            </button>
        </div>
    </div>
</div>
