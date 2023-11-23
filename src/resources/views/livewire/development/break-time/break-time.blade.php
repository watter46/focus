<div class="absolute top-0 left-0 z-30 flex items-center justify-center w-screen h-screen bg-sky-200 modal-bg"
    style="background-color: rgba(0, 0, 0, 0.8);"
    x-data="{
        isShow: false,
        breakTime: @entangle('breakTime'),
        time() {
            return this.breakTime;
        },
        start() {
            this.isShow = true;
            startBreakTime(this.time());
        },
        finish() {
            this.isShow = false;
            finishBreakTime();
        }
    }"
    @on-start-break-time.window="start"
    @finish-break-time.window="finish"
    x-show="isShow"
    x-cloak
    x-transition:enter="transition ease-out duration-700"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-700"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90">
    
    <div class="relative z-40 w-2/3 bg-gray-800 h-2/3 rounded-xl">
        <p class="p-5 text-center text-orange-600 text-8xl">BreakTime!!</p>

        <div class="flex items-center justify-center p-5">
            <p id="breakTime-countdown"
                class="text-center text-orange-600 text-8xl"
                style="
                    font-family: 'Century Gothic';
                    font-size: 90px;
                    line-height: 1;
                ">
            </p>
        </div>

        <div class="absolute bottom-0 right-0 flex justify-end w-full gap-4 p-5">
            <button class="py-3 text-white bg-gray-700 rounded-lg w-36 hover:bg-gray-600"
                wire:click="repeat"
                @click="finish">Repeat</button>
                
            <button class="py-3 text-white rounded-lg w-36 bg-sky-700 hover:bg-sky-600"
                wire:click="fresh"
                @click="finish">Fresh</button>
        </div>
    </div>

    @push('breakTime-script')
        @vite(['resources/js/development/breakTime/breakTime.js'])
    @endpush
</div>
