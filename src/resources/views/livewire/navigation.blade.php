<div class="w-full">
    <div class="h-8 py-5 text-sm font-semibold flex justify-start items-center">
        {{-- Projects --}}
        <div class="hover-tab h-5 ml-5 mr-1 p-5 flex justify-center items-center"
             wire:click="toProjects">
            <svg class="w-5 h-5 mr-1 fill-gray-500" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 429.76"><path fill-rule="nonzero" d="M96.3 0h242.43c-15.45 14.62-30.92 29.65-46 44.92l-.78.79H96.3c-13.9 0-26.55 5.7-35.72 14.87-9.17 9.17-14.87 21.82-14.87 35.71v237.17c0 27.86 22.73 50.59 50.59 50.59h319.4c27.7 0 50.59-22.89 50.59-50.59V100.71c12.47-14.55 25-28.94 37.48-43.14A94.985 94.985 0 0 1 512 96.29v237.17c0 52.91-43.39 96.3-96.3 96.3H96.3c-52.84 0-96.3-43.47-96.3-96.3V96.29C0 69.8 10.83 45.71 28.27 28.27 45.71 10.83 69.8 0 96.3 0zm92.81 146.72c22.15 12.77 36.58 23.38 53.76 42.31 44.55-71.71 94.83-113.36 157.71-169.76l6.15-2.37h68.8C383.28 119.36 314.7 201.19 245.77 324.73c-41.43-69.82-69.31-114.77-129.55-161.54l72.89-16.47z"></path>
            </svg>
            <label class="text-gray-400">Projects</label>
        </div>

        {{-- Timer --}}
        <div class="hover-tab h-5 mx-1 p-5 flex justify-center items-center"
             wire:click="toTimer">
            <svg class="w-5 h-5 mr-1 fill-gray-500" viewBox="0 0 101.5 122.88" style="enable-background:new 0 0 101.5 122.88" xml:space="preserve">
                <g><path d="M56.83,21.74c11.58,1.38,21.96,6.67,29.8,14.5c9.18,9.18,14.86,21.87,14.86,35.88c0,14.01-5.68,26.7-14.86,35.89 s-21.87,14.87-35.89,14.87c-14.01,0-26.7-5.68-35.88-14.87C5.68,98.83,0,86.14,0,72.13c0-14.01,5.68-26.7,14.86-35.88 c8.41-8.41,19.77-13.89,32.38-14.75v-8.51c0-0.13,0.01-0.26,0.02-0.38l-9.51,0c-1.56,0-2.83-1.28-2.83-2.83V2.83 C34.92,1.28,36.2,0,37.76,0h28.57c1.56,0,2.84,1.28,2.84,2.83v6.94c0,1.56-1.28,2.83-2.84,2.83h-9.51 c0.01,0.13,0.02,0.25,0.02,0.38V21.74L56.83,21.74L56.83,21.74z M54.82,64.55c2.7,1.45,4.53,4.3,4.53,7.58 c0,4.75-3.85,8.61-8.61,8.61c-4.75,0-8.61-3.85-8.61-8.61c0-3.28,1.84-6.13,4.53-7.58l0-19.72c0-2.25,1.82-4.08,4.07-4.08 c2.25,0,4.08,1.82,4.08,4.08L54.82,64.55L54.82,64.55L54.82,64.55z M96.33,37.07c1.97-4.7,1.74-9.63-1.08-12.92 c-3.38-3.96-9.5-4.41-15.17-1.63C86.08,26.65,91.54,31.45,96.33,37.07L96.33,37.07L96.33,37.07z M5.17,37.07 c-1.97-4.7-1.74-9.63,1.08-12.92c3.38-3.96,9.5-4.41,15.18-1.63C15.41,26.65,9.95,31.45,5.17,37.07L5.17,37.07L5.17,37.07z M80.87,42.01c-7.71-7.71-18.36-12.48-30.12-12.48c-11.76,0-22.41,4.77-30.12,12.48C12.92,49.72,8.15,60.37,8.15,72.13 c0,11.76,4.77,22.42,12.48,30.12s18.36,12.48,30.12,12.48c11.77,0,22.42-4.77,30.12-12.48s12.48-18.36,12.48-30.13 C93.35,60.37,88.58,49.72,80.87,42.01L80.87,42.01L80.87,42.01z"></path>
                </g>
            </svg>
            <label class="text-gray-400">Timer</label>
        </div>

        {{-- setting --}}
        <div class="hover-tab h-5 mx-1 p-5 flex justify-center items-center"
             wire:click="toSetting">
            <svg class="w-5 h-5 mr-1 fill-gray-500" viewBox="0 0 122.88 122.88">
                <defs><style>.cls-1{fill-rule:evenodd;}</style></defs>
                <path class="cls-1" d="M73.48,15.84A46.87,46.87,0,0,1,84.87,21L91,14.84a7.6,7.6,0,0,1,10.72,0L108,21.15a7.6,7.6,0,0,1,0,10.72l-6.6,6.6a46.6,46.6,0,0,1,4.34,10.93h9.52A7.6,7.6,0,0,1,122.88,57V65.9a7.6,7.6,0,0,1-7.58,7.58h-9.61a46.83,46.83,0,0,1-4.37,10.81L108,91a7.6,7.6,0,0,1,0,10.72L101.73,108A7.61,7.61,0,0,1,91,108l-6.34-6.35a47.22,47.22,0,0,1-11.19,5v8.59a7.6,7.6,0,0,1-7.58,7.58H57a7.6,7.6,0,0,1-7.58-7.58v-7.76a47.39,47.39,0,0,1-12.35-4.68L31.87,108a7.62,7.62,0,0,1-10.72,0l-6.31-6.31a7.61,7.61,0,0,1,0-10.72l4.72-4.72A47.38,47.38,0,0,1,14,73.48H7.58A7.6,7.6,0,0,1,0,65.9V57A7.6,7.6,0,0,1,7.58,49.4h6.35a47.2,47.2,0,0,1,5.51-12.94l-4.6-4.59a7.62,7.62,0,0,1,0-10.72l6.31-6.31a7.6,7.6,0,0,1,10.72,0l5,5A46.6,46.6,0,0,1,49.4,15V7.58A7.6,7.6,0,0,1,57,0H65.9a7.6,7.6,0,0,1,7.58,7.58v8.26ZM59.86,36.68a24.6,24.6,0,1,1-24.6,24.59,24.59,24.59,0,0,1,24.6-24.59Z">
                </path>
            </svg>
            <label class="text-gray-400">Setting</label>
        </div>
    </div>

    <style>
        .hover-tab:hover label {
            cursor: pointer;
        }
        .hover-tab:hover {
            border-bottom: 2px solid orange;
            cursor: pointer;
        }
    </style>
</div>
