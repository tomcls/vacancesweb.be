
@props([
    'color' => '',
    'size' => 'text-sm'
])
<x-button {{ $attributes->merge(['class' => 'mt-3 border'])}}  size="{{$size}}" color="{{$color}}">
    {{$slot}}
</x-button>