<label  class="inline-flex items-center text-sm space-x-2 cursor-pointer dark:text-gray-100 mx-2 ">
    <span class="relative">
        <input  {!! $attributes->merge(['class' => 'hidden peer']) !!}  type="checkbox" />
        <div class="w-14 h-8 rounded-full bg-gray-200 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-lighter dark:peer-focus:ring-primary-darker  peer-checked:bg-primary-light peer-checked:dark:bg-primary-light transition-all duration-300"></div>
        <div class="absolute inset-y-0 left-0 w-6 h-6 m-1 rounded-full peer-checked:right-0 peer-checked:left-auto  bg-white transition-all duration-300 transform translate-x-0"></div>
    </span>
</label>