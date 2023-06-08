{{--
-- Important note:
--
-- This template is based on an example from Tailwind UI, and is used here with permission from Tailwind Labs
-- for educational purposes only. Please do not use this template in your own projects without purchasing a
-- Tailwind UI license, or they’ll have to tighten up the licensing and you’ll ruin the fun for everyone.
--
-- Purchase here: https://tailwindui.com/
--}}

<div class="flex ">
    <textarea  rows="5" {{ $attributes->merge(['class' =>' block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md focus:ring-2  focus:ring-inset  focus:ring-sky-500 '])}} {{ $attributes }}></textarea>
</div>
