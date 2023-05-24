
@props([
    'color' => 'border-indigo-500 text-indigo-500 hover:bg-indigo-500 hover:text-white',
    'size' => 'text-sm'
])
<x-button wire:click="active" class=" mt-3 border-2" size="{{$size}}" color="{{$color}}">
    {{$slot}}
    <x-slot:trailingIcon>
      <x-icon.dright class="flex-shrink-0 "/>
    </x-slot:trailingIcon>
</x-button>