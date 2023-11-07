<div class="flex justify-center">
    <div class="w-full pb-5 px-14">
        <div class="flex justify-end mb-7">
            {{-- ToNewProjectButton --}}
            <button
                type="button"
                class="inline-flex items-center px-3 py-2 mx-2 text-sm font-medium text-center text-white rounded-lg whitespace-nowrap bg-lime-600 dark:focus:ring-lime-500 hover:bg-lime-500"
                wire:click="toNewProjectPage">
                New Project
            </button>
        </div>

        <div
            class="flex items-center h-16"
            x-data="{
                isEdit: false,
                isComplete: @entangle('form.isComplete')
            }"
            @close-project-name-input.window="isEdit = false">
            <div class="w-full">
                {{-- Display --}}
                <label class="bg-none" x-show="!isEdit" x-cloak>
                    <input
                        type="text"
                        id="projectName-display"
                        class="w-full text-4xl text-white border-0 outline-none cursor-auto focus:border-transparent focus:ring-0 bg-inherit"
                        value="{{ $form->projectName }}"
                        readonly
                    >
                </label>
            
                {{-- Input --}}
                <div
                    class="relative rounded-lg p-0.5 border-gray-300 appearance-none dark:bg-gray-800 dark:border-gray-600"
                    x-show="isEdit"
                    x-cloak>
                    @error('form.projectName')
                        <p class="absolute text-red-700 bottom-full">{{ $message }}</p>
                    @enderror
                    
                    <div class="flex">
                        {{-- UpdateInput --}}
                        <input
                            class="block w-full text-4xl border-0 border-b-2 border-gray-300 appearance-none dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            id="projectNameInput"
                            type="text"
                            placeholder="ProjectName"
                            wire:model="form.projectName"
                            wire:keydown.ctrl.enter.prevent="update"
                            @focus-end.window="$el.focus()"
                        >
                
                        {{-- UpdateButton --}}
                        <button
                            type="button"
                            class="inline-flex items-center px-3 py-2 mx-2 text-sm font-medium text-center text-white rounded-lg bg-lime-600 dark:focus:ring-lime-500 hover:bg-lime-500"
                            wire:click="update">
                            update
                        </button>
                    </div>
                </div>                        
            </div>

            <div class="flex items-center justify-end">
                {{-- EditButton --}}
                <button
                    id="edit_btn"
                    type="button"
                    class="inline-flex items-center px-3 py-2 mx-2 text-sm font-medium text-center text-white bg-gray-600 rounded-lg dark:focus:ring-lime-500 hover:bg-gray-500"
                    x-show="!isEdit"
                    x-cloak
                    @click="isEdit = true"
                    wire:click="focusEnd">
                    Edit
                </button>
        
                {{-- CancelButton --}}
                <button
                    id="cancel_btn"
                    type="button"
                    class="items-center px-3 py-2 mx-2 text-sm font-medium text-center text-white bg-gray-600 rounded-lg dark:focus:ring-lime-500 hover:bg-gray-500"
                    x-show="isEdit"
                    x-cloak
                    @click="isEdit = false">
                    cancel
                </button>

                {{-- Complete --}}
                <button
                    type="button"
                    class="flex items-center justify-center w-32 px-3 py-2 mx-2 text-sm font-medium text-center text-white rounded-lg bg-amber-600 dark:focus:ring-amber-500 hover:bg-amber-500"
                    wire:click="complete"
                    x-show="!isComplete"
                    x-cloak>
                    <x-icons.complete-button />
                    Complete
                </button>
        
                {{-- InComplete --}}
                <button
                    type="button"
                    class="flex items-center justify-center w-32 px-3 py-2 mx-2 text-sm font-medium text-center text-white rounded-lg bg-sky-600 dark:focus:ring-sky-500 hover:bg-sky-500"
                    wire:click="incomplete"
                    x-show="isComplete"
                    x-cloak>
                    <x-icons.incomplete-button />
                    InComplete
                </button>
            </div>
        </div>

        <div class="w-full my-3 border-b-2 border-gray-400"></div>
    </div>
</div>
