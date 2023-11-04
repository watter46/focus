<div class="flex px-12 mt-6" x-data>
    <div class="w-9/12 p-4 m-1">
        {{-- ProjectNameInput --}}
        <div class="p-0.5 mb-10 bg-gray-800 rounded-lg">
            <div class="bg-gray-800 " style="height: 18px;">
                @error('projectName')
                    <span class="px-2 text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <label for="title_input"></label>
            <input
                class="px-2.5 pb-1.5 w-full text-xl text-gray-900 bg-transparent dark:bg-gray-800 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer placeholder:text-sm"
                id="title_input"
                type="text"
                autocomplete="off"
                placeholder="ProjectName"
                wire:model="projectName"
                wire:keydown.ctrl.enter.prevent="create" />
        </div>

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
                    if (id !== 'create') return;

                    this.editor.prepend($refs.newTaskContent);
                }
            }">

            {{-- PrependCheckbox --}}
            <div class="flex justify-end w-full border border-gray-600 rounded-t-lg dark:bg-gray-700">
                <button
                    class="p-2 mx-2 text-gray-500 rounded cursor-pointer parent-checkbox-btn hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600"
                    type="button"
                    title="shift + alt"
                    @click="$dispatch('new-project-editor', 'create')">
                    <x-icons.add-task />
                </button>
            </div>
            
            {{-- TaskName --}}
            <div class="bg-gray-800 " style="height: 18px;">
                @error('name')
                    <span class="px-2 text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <label for="newTaskName_input"></label>
            <input class="w-full p-2 text-gray-900 border-0 border-b-2 border-gray-300 appearance-none placeholder:text-sm bg-gray-50 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                id="newTaskName_input"
                type="text"
                autocomplete="off"
                placeholder="TaskName"
                wire:model="name"
                @keydown.ctrl.enter.prevent="$wire.create($refs.newTaskContent.value)" />

            {{-- Content --}}
            <div class="px-2 py-2 rounded-b-lg dark:bg-gray-800">
                <div class="bg-gray-800 " style="height: 18px;">
                    @error('content')
                        <span class="px-2 text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <label class="sr-only" for="create-task"></label>
                <textarea
                    class="block w-full px-0 text-sm text-gray-800 bg-white border-0 resize-none focus:outline-none dark:bg-gray-800 focus:ring-0 dark:text-white placeholder:text-sm"
                    id="create-task" 
                    name="create-task"
                    rows="8"
                    placeholder="Write an task..."
                    x-ref="newTaskContent"
                    wire:model="content"
                    @input="resize($el)"
                    @new-project-editor.window="prependOrNone($event.detail)"
                    @keydown="keydownShiftAlt($event)"
                    @keydown.ctrl.enter.prevent="$wire.create($refs.newTaskContent.value)">
                </textarea>
            </div>

            <div class="flex justify-end mt-2">
                {{-- CreateButton --}}
                <button
                    class="inline-flex items-center px-5 py-2 font-medium text-center text-white rounded-lg bg-lime-600 focus:ring-4 dark:focus:ring-lime-500 hover:bg-lime-500"
                    title="control + enter"
                    @click="$wire.create($refs.newTaskContent.value)">
                    Create
                </button>
            </div>
        </div>
    </div>
    
    {{-- Label --}}
    <div class="w-3/12 mt-6">
        <div class="w-full"
            x-data="{ isOpen: false }"
            @click.outside="isOpen = false">

            <div class="relative mb-2 cursor-pointer">
                <button class="flex items-center w-full px-1 py-1 hover:bg-sky-800 hover:rounded"
                    @click="isOpen = !isOpen">
                    <p class="text-xl font-black text-white pointer-events-nop">Label</p>
                    <x-icons.label />
                </button>

                <div x-cloak x-show="isOpen">
                    <div class="absolute w-full mt-1 bg-gray-800 border border-gray-400 rounded-lg top-full">
                        <p class="px-2 py-3 font-medium text-white pointer-events-none">
                            Select Label
                        </p>

                        @foreach ($LABELS as $LABEL)
                            <div class="label-hover flex p-1.5 text-xs text-white border-t border-gray-700 hover:bg-sky-800"
                                wire:click.prevent="update('{{ $LABEL->get('text') }}')"
                                @click="isOpen = false">

                                @if (!$this->isSame($LABEL))
                                    <span class="{{ $LABEL->get('class') }}"></span>
                                    <p class="label-text">{{ $LABEL->get('text') }}</p>
                                @endif

                                @if ($this->isSame($LABEL))
                                    <span class="{{ $LABEL->get('class') }}"></span>
                                    <p class="label-text-selected">{{ $LABEL->get('text') }}</p>
                                    <x-icons.selected-cross />
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <p class="p-1 text-lg font-medium text-white focus:outline-none bg-inherit">
                {{ $label->get('text') }}
            </p>

            <div class="w-full mt-3 border-b-2 border-gray-400"></div>
        </div>
    </div>

    @push('label-style')
        @vite(['resources/css/utils/label.css'])
    @endpush

    @push('editor-script')
        @vite(['resources/js/project/editor.js'])
    @endpush
</div>