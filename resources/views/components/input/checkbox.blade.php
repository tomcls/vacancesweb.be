@props([
    'label'=>'',
    'comment' => '',
    'id'=> null,
    'error' => false,
    'helpText' => false,
])
<div class="relative flex items-start">
    <div class="flex h-6 items-center">
      <input {{ $attributes }}   type="checkbox" 
      class="h-4 w-4 rounded border-gray-300 text-sky-500 focus:ring-sky-600">
    </div>
    <div class="ml-3 text-sm leading-6">
      <label for="{{ $id }}" class="font-medium text-gray-900">{{ $label }}</label>
      <p id="{{ $id }}" class="text-gray-500">{{$comment}}</p>
    </div>
  </div>