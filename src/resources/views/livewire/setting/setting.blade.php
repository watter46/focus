<div>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="text-3xl font-semibold leading-tight text-gray-200">
                {{ __('Setting') }}
            </h2>
        </div>
    </x-slot>

    <div class="h-full pt-6 bg-sky-950">
        <div class="h-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="min-h-full shadow-xl sm:rounded-lg">            
                {{-- Timer設定 --}}
                <div class="relative p-3 border-2 border-gray-300 rounded-lg"
                    style="min-height: 40vh;"
                    x-data="{
                        defaultTime: @entangle('defaultTime'),
                        breakTime:   @entangle('breakTime'),
                        showSave: false,
                        toggle(time) {
                            Number.isInteger(time)
                                ? this.showSave = false
                                : this.showSave = true
                        }
                    }"
                    x-cloak>
                    <p class="text-2xl text-white underline underline-offset-8">Timer</p>
            
                    {{-- DefaultTime --}}
                    <div class="w-4/6 p-5 m-auto my-3">
                        <div class="flex justify-center w-full">
                            <div class="flex items-center w-full">
                                <div class="flex items-center justify-center w-full py-1">
                                    <div class="relative w-full">
                                        <label for="defaultTime-range"
                                            class="block mb-2 text-2xl font-bold text-gray-300">
                                            Default Time
                                        </label>
                                        <input id="defaultTime-range"
                                            class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer range-lg dark:bg-gray-700"
                                            type="range"
                                            min="1" max="90"
                                            x-model="defaultTime">
                                        
                                        <p class="absolute bottom-0 left-0 -mb-6 -ml-1 text-gray-300">1</p>
                                        <p class="absolute bottom-0 right-0 -mb-6 -mr-1 text-gray-300">90</p>
                                    </div>
                                </div>
                                
                                <div class="relative flex items-baseline w-20 h-full text-3xl text-white"
                                    style="font-family: 'Century Gothic';">
                                    <p class="absolute bottom-0 px-2" x-text="defaultTime"></p>
                                    <p class="absolute bottom-0 right-0 ml-2 text-xl">m</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- BreakTime --}}
                    <div class="w-4/6 p-5 m-auto my-3">
                        <div class="flex justify-center w-full">
                            <div class="flex items-center justify-center w-full">
                                <div class="relative flex items-center justify-center w-full py-1">
                                    <div class="w-full">
                                        <label for="breakTime-range"
                                            class="block mb-2 text-2xl font-bold text-gray-300">
                                            Break Time
                                        </label>
                                        <input id="breakTime-range"
                                            class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer range-lg dark:bg-gray-700"
                                            type="range"
                                            min="1" max="30"
                                            x-model="breakTime">
                                        
                                        <div class="absolute bottom-0 left-0 -mb-6 -ml-1 text-gray-300">1</div>
                                        <div class="absolute bottom-0 right-0 -mb-6 -mr-1 text-gray-300">30</div>
                                    </div>
                                </div>
                                
                                <div class="relative flex items-baseline w-20 h-full text-3xl text-white"
                                    style="font-family: 'Century Gothic';">
                                    <p class="absolute bottom-0 px-2" x-text="breakTime"></p>
                                    <p class="absolute bottom-0 right-0 ml-2 text-xl">m</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end w-5/6 mt-10">
                        <button class="py-1 text-white rounded px-7 bg-sky-700 hover:bg-sky-600"
                            wire:click="update"
                            :class="showSave || ['opacity-50', 'pointer-events-none']"
                            x-init="
                                $watch('defaultTime', time => toggle(time))
                                $watch('breakTime',   time => toggle(time))
                            ">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>