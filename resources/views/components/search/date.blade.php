{{--
-- Important note:
--
-- This template is based on an example from Tailwind UI, and is used here with permission from Tailwind Labs
-- for educational purposes only. Please do not use this template in your own projects without purchasing a
-- Tailwind UI license, or they’ll have to tighten up the licensing and you’ll ruin the fun for everyone.
--
-- Purchase here: https://tailwindui.com/
--}}

<div x-data="{ 
        value: @entangle($attributes->wire('model')), 
        picker: null 
    }"
    x-init="new Pikaday({ 
        field: $refs.input, 
        format: 'D-M-YYYY', 
        onOpen() {  }, 
        onDraw() { console.log('draw') },
        onSelect: function() {console.log(this.getMoment()); }
    })"
    x-on:change="value = $event.target.value">
    <div class="hidden sm:block relative  rounded-none shadow-sm border-l-0 border-r-0">
      <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 border-l-0">
        <x-icon.calendar class="h-5 w-5 text-blue-400" />
      </div>
      <input 
      {{ $attributes->whereDoesntStartWith('wire:model') }}
      x-ref="input"
      x-bind:value="value"
       class="block w-full border-l-0 border-r-0 rounded-none border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" placeholder="you@example.com">
    </div>
</div>
