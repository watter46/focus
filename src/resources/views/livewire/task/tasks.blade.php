<div class="mt-6 px-12">
    {{-- Project Title --}}
    <div class="flex justify-center">
        <div class="py-5 px-14 w-full">
            <div class="flex">
                <label class="w-full text-4xl text-white">{{ $project['project'] }}</label>

                <div class="flex justify-end items-center">
                    <button type="button"
                            class="mx-2 px-3 py-2 inline-flex items-center text-sm font-medium text-center text-white bg-gray-600 rounded-lg dark:focus:ring-lime-500 hover:bg-gray-500"
                            onclick="">
                        Edit
                    </button>

                    <button type="submit"
                            class="mx-2 px-3 py-2 whitespace-nowrap inline-flex items-center text-sm font-medium text-center text-white bg-lime-600 rounded-lg dark:focus:ring-lime-500 hover:bg-lime-500"
                            wire:click="toNewProject">
                        New Project
                    </button>
                </div>
            </div>

            <div class="w-full my-5 border-b-2 border-gray-400"></div>
        </div>
    </div>

    {{-- main --}}
    <div class="flex justify-center">
        <div class="py-5 pr-5 w-8/12">
            @foreach ($project['tasks'] as $index => $tasks)
                <div>
                    <livewire:task.task-list :project_id="$project['id']"
                                             :tasks="$tasks"
                                             :index="$index"
                                             :wire:key="$tasks['id']" />
                </div>
            @endforeach

            <div class="w-full mt-3 border-b-2 border-gray-400"></div>
    
            {{-- TextEditor --}}
            <div class="mt-3">
                <livewire:task.editor :project_id="$project['id']" />
            </div>
        </div>

        <div class="w-3/12">
            {{-- Tag --}}
            <div class="h-full flex items-center">
                <div class="w-full h-5/6">
                    <div class="p-2 border-b-2 border-gray-400">
                        <div class="mb-1 grid grid-cols-12 gap-4">
                            <label class="col-start-1 col-end-2 font-black text-white">Tag</label>
                            <svg class="w-5 h-5 col-start-11 fill-white" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 510 511.91"><path fill-rule="nonzero" d="M375.81 301.17 167.58 509.4a8.552 8.552 0 0 1-12.11 0L2.51 356.45a8.564 8.564 0 0 1 0-12.12L210.74 136.1a8.51 8.51 0 0 1 6.06-2.52h83.97C329.63 58.8 366.74 20.67 401.25 6.67c32.8-13.31 63.44-5.58 83.86 13.35 20.29 18.81 30.27 48.72 21.95 79.92-10.07 37.78-47.22 77.46-126.25 99.74-1.92.54-3.86.5-5.65.01l3.13 95.16c.08 2.45-.89 4.69-2.5 6.3l.02.02zm-52.48-167.59h41.39c4.74 0 8.57 3.84 8.57 8.57 0 .15 0 .3-.01.44l1.22 37.01.62-.2c70.54-19.89 103.2-53.39 111.58-84.82 6.2-23.24-1.07-45.37-15.94-59.15-14.74-13.66-37.21-19.12-61.6-9.22-28.86 11.71-60.15 44.01-85.83 107.37zm.52 59.45c1.16 1.33 2 2.94 2.37 4.76 1.03 2.77 1.6 5.76 1.6 8.88 0 7.02-2.85 13.38-7.45 17.98s-10.96 7.45-17.98 7.45-13.37-2.85-17.97-7.45a25.315 25.315 0 0 1-7.45-17.98c0-5.89 2-11.32 5.37-15.63 3.82-14.39 7.89-27.8 12.19-40.31h-74.18L20.69 350.39l140.83 140.83 199.57-199.57-4.63-140.92h-39.67c-3.46 9.66-6.79 19.96-9.99 30.9 5.26.93 9.96 3.45 13.57 7.06 1.31 1.32 2.48 2.77 3.48 4.34z"></path>
                            </svg>
                        </div>
                        <label class="text-sm font-medium text-white">Not yet</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
