@props(['slot'=>false,'href'=>false,'name'=>false])
<!-- Main footer -->
<footer class="w-full flex items-center justify-between p-4 bg-white border-t dark:bg-darker dark:border-primary-darker">
    <div class="mx-auto text-center text-sm text-primary-dark dark:text-light md:text-lg md:font-bold"> {{ $slot }} &copy; 2024</div>
    <div>
      <a href="{{ $href }}" target="_blank" class="text-blue-500 hover:underline"> {{ $name }} </a>
    </div>
</footer>