<div class="w-full">
    <div class="relative mb-2 cursor-pointer"
        x-data="{ isOpen: false }"
        @click.outside="isOpen = false">
        <button class="flex items-center w-full px-1 py-1 hover:bg-sky-800 hover:rounded"
            @click="isOpen = !isOpen">
            <p class="text-xl font-black text-white pointer-events-nop">Label</p>
            <x-icons.label />
        </button>

        <div x-cloak x-show="isOpen" class="absolute w-full mt-1 bg-gray-800 border border-gray-400 rounded-lg">
            <p class="px-2 py-3 font-medium text-white pointer-events-none">
                {{-- Select Label --}}
                @if(isset($title))
                    {{ $title }}
                @endif
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

    <p class="p-1 text-lg font-medium text-white focus:outline-none bg-inherit">
        {{ $label->get('text') }}
    </p>

    <div class="w-full mt-3 border-b-2 border-gray-400"></div>

    @push('label-style')
        @vite(['resources/css/utils/label.css'])
    @endpush
</div>