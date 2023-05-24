<div>
    <h1 class="text-2xl font-semibold text-gray-900">Profile</h1>

    <form wire:submit.prevent="save">
        <div class="space-y-1">
            <x-input.group inline label="firstname" for="firstname" :error="$errors->first('user.firstname')">
                <x-input.text wire:model="user.firstname" id="firstname"  >
                    <x-slot:leadingIcon>
                        <svg class="h-6 w-6 flex-shrink-0 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                        </svg>
                    </x-slot:leadingIcon>
                </x-input.text>
            </x-input.group>

            <x-input.group inline label="lastname" for="lastname" :error="$errors->first('user.lastname')">
                <x-input.text wire:model="user.lastname" id="lastname"  >
                    <x-slot:leadingIcon>
                        <svg class="h-6 w-6 flex-shrink-0 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                        </svg>
                    </x-slot:leadingIcon>
                </x-input.text>
            </x-input.group>

            <x-input.group inline label="email" for="email" :error="$errors->first('user.email')">
                <x-input.text wire:model="user.email" id="email" placeholder="john@doe.com" >
                    <x-slot:leadingIcon>
                        <svg class="h-6 w-6 flex-shrink-0 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
                          </svg>
                    </x-slot:leadingIcon>
                </x-input.text>
            </x-input.group>

            <x-input.group inline label="phone" for="phone" :error="$errors->first('user.phone')">
                <x-input.text wire:model="user.phone" id="phone" placeholder="+99.99.99.99" />
            </x-input.group>

            {{-- <x-input.group label="About" for="about" :error="$errors->first('user.about')" help-text="Write a few sentances about yourself.">
                <x-input.rich-text wire:model.defer="user.about" id="about" />
            </x-input.group> --}}

            <x-input.group inline label="Photo" for="photo" :error="$errors->first('upload')">
                <x-input.file-upload wire:model="upload" id="photo">
                    <span class="h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                        @if ($upload)
                            <img src="{{ $upload->temporaryUrl() }}" alt="Profile Photo">
                        @else
                            <img src="{{ auth()->user()->avatarUrl() }}" alt="Profile Photo">
                        @endif
                    </span>
                </x-input.file-upload>
            </x-input.group>
        </div>

        <div class="mt-8 border-t border-gray-200 pt-5">
            <div class="space-x-3 flex justify-end items-center">
                <span x-data="{ open: false }" x-init="
                        @this.on('notify-saved', () => {
                            if (open === false) setTimeout(() => { open = false }, 2500);
                            open = true;
                        })
                    " x-show.transition.out.duration.1000ms="open" style="display: none;" class="text-gray-500">Saved!</span>

                <span class="inline-flex rounded-md shadow-sm">
                    <button type="button" class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                        Cancel
                    </button>
                </span>

                <span class="inline-flex rounded-md shadow-sm">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                        Save
                    </button>
                </span>
            </div>
        </div>
    </form>
</div>