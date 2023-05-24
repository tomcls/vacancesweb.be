{{--
-- Important note:
--
-- This template is based on an example from Tailwind UI, and is used here with permission from Tailwind Labs
-- for educational purposes only. Please do not use this template in your own projects without purchasing a
-- Tailwind UI license, or they’ll have to tighten up the licensing and you’ll ruin the fun for everyone.
--
-- Purchase here: https://tailwindui.com/
--}}
@props([
    'id' => null,
])
<div
    class="rounded-md shadow-sm"
    x-data="{
            value: @entangle($attributes->wire('model')),
            initEditor: null,
            setValue() { this.initEditor(this.value) }
        }"
    x-init="
        initEditor = suneditor.init({
            value:value,
            height: 200,
            plugins: plugins,
            buttonList: [
                [
                'undo', 'redo',
                 'fontSize', 'formatBlock',
                'paragraphStyle', 'blockquote',
                'bold', 'underline', 'italic', 'strike', 'subscript', 'superscript',
                'fontColor', 'hiliteColor', 'textStyle',
                'removeFormat',
                'outdent', 'indent',
                'align', 'horizontalRule', 'list', 'lineHeight',
                'table', 'link',
                'fullScreen', 'showBlocks', 'codeView',
                'preview', 'print',
                ]
            ],
            defaultStyle: 'font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, \'Noto Sans\', sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\', \'Noto Color Emoji\'',
            font:[
                'Arial', 'Comic Sans MS', 'Courier New', 'Impact',
                'Georgia','tahoma', 'Trebuchet MS', 'Verdana'
              ]
        });
        const editor =initEditor.create( '{{ $id }}');
        editor.onBlur = function (e, core) { 
            value = editor.getContents();
            //Livewire.emitSelf('setDescription');
        }
        "
    {{ $attributes->whereDoesntStartWith('wire:model') }}
    wire:ignore
>
    <input id="x" type="hidden">
       
    <textarea 
        id="{{ $id }}"
        x-ref="trix"
        class="form-textarea 
            ring-1 ring-inset 
            focus:ring-indigo-600 
            focus:ring-inset 
            ring-gray-300 
            focus:ring-2 
            rounded-md 
            block w-full 
            transition 
            duration-150 
            ease-in-out 
            sm:text-sm 
            sm:leading-5">
    </textarea>
</div>
