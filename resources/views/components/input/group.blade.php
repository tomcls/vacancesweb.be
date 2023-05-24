@props([
    'label',
    'for',
    'error' => false,
    'helpText' => false,
    'inline' => false,
    'paddingless' => false,
    'borderless' => false,
    'hidden' => false
])

@if($inline)
    <div {{ $attributes->merge(['class' => ($hidden ? 'hidden' : '  sm:grid ') .' sm:grid-cols-5  sm:gap-4 sm:items-start' .($borderless ? '' : ' sm:border-t ') .'sm:border-gray-200' .($paddingless ? '' : ' sm:py-5 ') ])}} >
        <label for="{{ $for }}" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2  ">
            {{ $label }}
        </label>

        <div class="relative mt-2 rounded-md shadow-sm col-span-3">
            {{ $slot }}

            @if ($error)
                <div class="mt-1 text-red-500 text-sm">{{ $error }}</div>
            @endif

            @if ($helpText)
                <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
            @endif
        </div>
    </div>
@else

<div {{ $attributes->merge(['class' => ($hidden ? 'hidden' : '   ') .''  ])}}>
    <label for="{{ $for }}" class="block text-sm font-medium leading-5 text-gray-700 ">{{ $label }}</label>

    <div class="relative mt-2 rounded-md shadow-sm">
        {{ $slot }}

        @if ($error)
            <div class="mt-1 text-red-500 text-sm">{{ $error }}</div>
        @endif

        @if ($helpText)
            <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
        @endif
    </div>
</div>
@endif

    
     
      
    