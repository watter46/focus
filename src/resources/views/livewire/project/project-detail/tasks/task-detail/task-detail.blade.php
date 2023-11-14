<div
    id="taskList"
    class="relative mt-4 text-gray-200 border-2 rounded-lg"
    :class="isComplete ? 'border-green-600' : 'border-gray-600'"
    x-data="{
        isOpen: false,
        isEdit: false,
        isComplete: @entangle('isComplete')
    }"
    @disable-editing="isEdit = false"
    x-cloak>
    
    {{-- ActionMenu --}}    
    <div>
        <div class="relative py-0.5 pr-3 rounded-t-lg w-full flex justify-end bg-gray-800"
            @click="isOpen = !isOpen">
            <p class="text-white cursor-pointer">
                ・・・
            </p>
        </div>
    
        <div
            x-show="isOpen"
            x-cloak
            class="absolute right-0 py-2 bg-gray-700 rounded-lg x-10 w-36">
            
            {{-- EditButton --}}
            <button class="w-full px-3 py-1.5 text-gray-300 font-medium cursor-pointer hover:bg-cyan-500 text-start"
                @click="
                    isOpen = false,
                    isEdit = true
                ">
                Edit
            </button>
    
            <span class="my-2 border-b-2 border-gray-400"></span>
    
            {{-- CompleteButton --}}
            <button class="w-full hover:bg-cyan-500" x-show="!isComplete" x-cloak>
                <p class="px-3 text-start py-1.5 text-gray-300 font-medium cursor-pointer"
                    wire:click="complete">
                    Complete
                </p>
            </button>
            
            {{-- InCompleteButton --}}
            <button class="w-full hover:bg-cyan-500" x-show="isComplete" x-cloak>
                <p class="px-3 text-start py-1.5 text-gray-300 font-medium cursor-pointer"
                    wire:click="incomplete">
                    InComplete
                </p>
            </button>
        </div>
    </div>

    {{-- Task --}}
    <div x-show="!isEdit" x-cloak>
        {{-- Title --}}
        <div id="title" class="flex items-center justify-center bg-gray-800">
            <p class="text-xl cursor-pointer">
                {{ $task->name }}
            </p>
        </div>

        {{-- Content --}}
        <livewire:project.project-detail.tasks.task-detail.task-content
            :$taskId
            :projectId="$task->project_id"
            :content="$task->content"
            :wire:key="$task->content" />
    </div>

    {{-- UpdateUpdater --}}
    <div x-show="isEdit" x-cloak>
        <div class="mt-3"
            x-data="{
                editor: new Editor(),
                resize(el) {
                    el.setAttribute('style', `height: auto;`),
                    el.setAttribute('style', `height: ${el.scrollHeight}px;`)
                },
                // Alpinejsでshift + altのkeydownメソッドがなぜか使えないためjavascriptで書く
                keydownShiftAlt(e) {
                    if (!e.shiftKey) return;
                    if (!e.altKey)   return;
                    
                    this.editor.prepend(e.target);
                },
                prependOrNone(id) {
                    if (id !== '{{ $task->id }}') return;

                    this.editor.prepend($refs.newTaskContent);
                },
                update() {
                    $wire.set('content', $refs.newTaskContent.value);
                    $wire.update();
                },
                cancel() {
                    $dispatch('disable-editing');
                }
            }">

            {{-- PrependCheckbox --}}
            <div class="flex justify-end w-full border border-gray-600 rounded-t-lg dark:bg-gray-700">
                <button
                    class="p-2 mx-2 text-gray-500 rounded cursor-pointer parent-checkbox-btn hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600"
                    type="button"
                    title="shift + alt"
                    @click="$dispatch('checkbox', '{{ $task->id }}')">
                    <x-icons.add-task />
                </button>
            </div>
            
            {{-- NameInput --}}
            <div x-id="['update-taskName-input']">
                <div class="bg-gray-800 " style="height: 18px;">
                    @error('name')
                        <span class="px-2 text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <label :for="$id('update-taskName-input')"></label>
                <input 
                    :id="$id('update-taskName-input')"
                    class="w-full p-2 text-gray-900 border-0 border-b-2 border-gray-300 appearance-none placeholder:text-sm bg-gray-50 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    autocomplete="off"
                    placeholder="TaskName"
                    wire:model="name"
                    @keydown.ctrl.enter.prevent="$wire.update($refs.newTaskContent.value)" />
            </div>

            {{-- ContentInput --}}
            <div x-id="['update-taskName-input']" class="rounded-b-lg">
                <div class="bg-gray-800 " style="height: 18px;">
                    @error('content')
                        <span class="px-2 text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <label class="sr-only" :for="$id('update-taskName-input')"></label>
                <textarea
                    :id="$id('update-taskName-input')"
                    class="block w-full p-2 text-sm text-gray-800 bg-white border-0 resize-none focus:outline-none dark:bg-gray-800 focus:ring-0 dark:text-white placeholder:text-sm"
                    rows="8"
                    placeholder="Write an task..."
                    x-ref="newTaskContent"
                    wire:model="content"
                    @input="resize($el)"
                    @checkbox.window="prependOrNone($event.detail)"
                    @keydown="keydownShiftAlt($event)"
                    @keydown.ctrl.enter.prevent="update">
                </textarea>
            </div>

            <div class="flex justify-end gap-4 mt-2">
                {{-- CancelButton --}}
                <button
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-gray-600 rounded-lg focus:ring-4 hover:bg-gray-500"
                    @click="cancel">
                    Cancel
                </button>
                
                {{-- UpdateButton --}}
                <button
                    class="inline-flex items-center px-5 py-2 font-medium text-center text-white rounded-lg bg-lime-600 focus:ring-4 dark:focus:ring-lime-500 hover:bg-lime-500"
                    title="control + enter"
                    @click="update">
                    Update
                </button>
            </div>
        </div>
    </div>

    @push('editor-script')
        @vite(['resources/js/Project/editor.js'])
    @endpush
</div>