
@props([
    'color' => 'border-sky-500 text-sky-500 hover:bg-sky-400 hover:text-white',
    'size' => 'text-sm',
    'trailingIcon' => true
])
<x-button {{ $attributes->merge(['class' => 'mt-3 border']) }}  size="{{$size}}" color="{{$color}}">
    {{$slot}}
    @if($trailingIcon)
      <x-slot:trailingIcon>
        <x-icon.dright class="flex-shrink-0 "/>
      </x-slot:trailingIcon>
    @endif
</x-button>