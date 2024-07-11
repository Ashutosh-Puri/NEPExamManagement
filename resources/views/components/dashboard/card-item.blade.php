@props(['slot'=>false])
<div {{ $attributes->merge(['type' => 'submit', 'class' => ' mt-2  w-full  rounded-xl border-2  px-3 py-2 text-gray-800 bg-white dark:border-primary']) }} >
   <span class="font-bold"> {{ $slot }} </span> 
</div>