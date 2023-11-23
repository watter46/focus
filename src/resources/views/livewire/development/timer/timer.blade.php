<div class="flex justify-center w-full mx-auto text-gray-300"
    id="timer-component">
    <div class="ml-40 h-44" wire:ignore>
        {{-- TimerDisplay --}}
        <div class="timer"
            x-data="{
                defaultTime  : @entangle('defaultTime'),
                remainingTime: @entangle('remainingTime')
            }"
            x-effect="setupTime(defaultTime, remainingTime)"
            @on-reset-timer.window="setupTime(defaultTime, remainingTime)">
            
            <div class="flex justify-center w-96">
                <p
                    id="countdown"
                    style="
                        font-family: 'Century Gothic';
                        font-size: 90px;
                        line-height: 1;
                    ">
                </p>
            </div>
        </div>

        {{-- Cursor --}}
        <div class="relative h-10 w-96">
            <x-icons.concave-line />
            <div id="cursor" class="absolute w-5 h-5 rounded-full -top-3 -left-3 bg-lime-400"></div>
        </div>
    </div>
    
    <div class="flex flex-wrap justify-center ml-5 h-44">
        {{-- TimeSetButton --}}
        <div class="flex items-center justify-center w-full">
            <div class="relative w-full"
                x-data="{ 
                    isOpen: false,
                    muteTimeSet: @entangle('muteTimeSet'),
                }"
                @click.outside="isOpen = false">
                <div id="time-set-btn"
                    class="relative flex items-center px-2 py-1 bg-gray-500 cursor-pointer"
                    :class="[
                        muteTimeSet ? 'mutedTimeSetBtn' : '',
                        isOpen      ? 'rounded-t-lg'    : 'rounded-lg'
                    ]"
                    x-cloak>
                    <div class="flex w-full cursor-pointer gap-x-2" @click="isOpen = !isOpen">
                        <x-icons.clock />
                        <p class="pointer-events-none">SET</p>
                    </div>
                </div>

                <div class="absolute z-10 w-full rounded-b-lg h-28 bg-sky-900"
                    id="time-setter"
                    x-cloak
                    x-show="isOpen">
                    <div class="grid grid-cols-3 mt-3">
                        <button class="px-2 py-2 mx-3 text-xl text-white bg-gray-500 rounded-full cursor-pointer hover:bg-gray-400 "
                            wire:click="setTime($event.target.textContent * 60)"
                            @click="isOpen = false">30</button>
                        <button class="px-2 py-2 mx-3 text-xl text-white bg-gray-600 rounded-full cursor-pointer hover:bg-gray-500 "
                            wire:click="setTime($event.target.textContent * 60)"
                            @click="isOpen = false">60</button>
                        <button class="px-2 py-2 mx-3 text-xl text-white bg-gray-700 rounded-full cursor-pointer hover:bg-gray-600 "
                            wire:click="setTime($event.target.textContent * 60)"
                            @click="isOpen = false">90</button>
                            
                        <button class="px-2 py-2 mx-3 text-xl text-white bg-gray-700 rounded-full cursor-pointer hover:bg-gray-600 "
                            wire:click="setTime($event.target.textContent)"
                            @click="isOpen = false">2</button>
                    </div>
                    
                    <div class="flex justify-center mt-3">
                        <button class="w-full py-1 mx-2 rounded bg-sky-600 hover:bg-sky-500"
                            wire:click="initialize"
                            @click="isOpen = false">Default</button>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="flex items-center justify-center"
            x-data="{
                muteStart: @entangle('muteStart'),
                muteClear: @entangle('muteClear'),
                isShowStart: true,
                start() {
                    $store.timer.start();
                    this.isShowStart = false;
                },
                stop() {
                    $store.timer.stop();
                    this.isShowStart = true;
                },
                clear() {
                    this.isShowStart = true;
                }
            }"
            @on-reset-timer.window="isShowStart = true"
            @timer-killed.window="stop()">
            <div x-cloak>
                {{-- StartButton --}}
                <button id="start"
                    class="rounded-full hover:bg-sky-900"
                    :class="muteStart ? 'mutedStartBtnColor' : 'startBtnColor'"
                    x-show="isShowStart"
                    wire:click="start"
                    @click="start()">
                    <x-icons.start-button /> 
                </button>
    
                {{-- StopButton --}}
                <button id="stop"
                    class="rounded-full hover:bg-sky-900 stopBtnColor"
                    x-show="!isShowStart"
                    wire:click="stop(getRemainingTime())"
                    @click="stop()">
                    <x-icons.stop-button /> 
                </button>

                {{-- ClearButton --}}
                <button id="clear"
                    class="ml-3 rounded-full hover:bg-sky-900"
                    :class="muteClear ? 'mutedClearBtnColor' : 'clearBtnColor'"
                    wire:click="clear"
                    @click="clear()">
                    <x-icons.clear-button />
                </button>
            </div>
        </div>
    
        @push('buttons-style')
            @vite(['resources/css/development/timer/buttons.css'])
        @endpush
    </div>
</div>