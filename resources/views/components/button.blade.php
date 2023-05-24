
@props([
    'color' => 'bg-indigo-600 border-indigo-500 hover:bg-indigo-700 hover:border-indigo-700 focus-visible:outline-indigo-600',
    'leadingIcon' => null,
    'trailingIcon' => null,
    'textColor' => null,
    'size' => 'text-sm'
])
@php
$px='';
$py='';

switch ($size) {
    case 'text-sm':
        $px='px-2.5';
        $py='py-1.5';
        break;
    case 'text-md':
        $px='px-3';
        $py='py-2';
        break;
    case 'text-lg':
        $px='px-3.5';
        $py='py-2.5';
        break;
    
    default:
        # code...
        break;
}

@endphp

<button 
    {{ $attributes->merge(['class' => ' 
        '.$color.'
        '.$size.'
        border-1
        rounded-xl
        focus-visible:outline 
        focus-visible:outline-2 
        focus-visible:outline-offset-2  
        shadow-sm
        font-semibold 
        '.$textColor.' '. 
        ($leadingIcon ? ' inline-flex gap-x-1.5 items-center '.$px.' '.$py.' ':'').
        ($trailingIcon ? ' inline-flex gap-x-1.5 items-center '.$px.' '.$py.' ':'')
        ]) }}
    type="button" >

    @if ($leadingIcon)
        {{ $leadingIcon }}
    @endif   

   <span class="text-gray-700 font-medium">{{$slot}}</span> 

    @if ($trailingIcon)
        {{ $trailingIcon }}
    @endif 
</button>
