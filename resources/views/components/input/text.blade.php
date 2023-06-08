{{--
    block w-full rounded-md border-0 py-1.5 pr-10 text-red-900 ring-1 ring-inset ring-red-300 placeholder:text-red-300 focus:ring-2 focus:ring-inset focus:ring-red-500 sm:text-sm sm:leading-6
--}}

@props([
    'leadingAddOn' => false,
    'leadingIcon' => false,
])

<div {{ $attributes->merge([
    'class' => '' . ($leadingAddOn ? 'mt-2 flex ' : '') . ($leadingIcon ? 'relative mt-2 ' : '').' rounded-md shadow-sm']) }}
    >
    @if ($leadingAddOn)
    <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 px-3 text-gray-500 sm:text-sm">
        {{ $leadingAddOn }}
    </span>
    @endif
    @if ($leadingIcon)
    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2  ">
        {{ $leadingIcon }}
    </div>
    @endif
    <input {{ $attributes->merge(['class' => 'rounded-md '. (!$leadingIcon ? ' pl-2' : '').'
    block 
    w-full 
    min-w-0 
    flex-1 
    border-0 
    py-1.5 
    text-gray-900 
    ring-1 
    ring-inset 
    ring-gray-300 
    placeholder:text-gray-400 
    focus:ring-2 
    focus:ring-inset 
    focus:ring-sky-500 
    sm:text-sm 
    sm:leading-6' . ($leadingAddOn ? ' rounded-none rounded-r-md ' : '') . ($leadingIcon ? '   pl-10 rounded-md' : ''). ' ']) }}/>
            
</div>
