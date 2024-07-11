<a wire:navigate href="{{ url('/') }}" class="h-13 py-0.5  border-b border-r flex items-center  dark:border-primary-darker dark:bg-darker  ocus:outline-none focus:text-gray-100 focus:bg-opacity-50 overflow-hidden">

   @php
      $base64Image =  'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/shikshan-logo.png')));
   @endphp
    <img class="h-14 w-32 mx-auto flex-shrink-0" src="{{ $base64Image }}" alt="">
</a>
{{-- <span class="h-13 py-0.5  border-b border-r flex items-center  dark:border-primary-darker dark:bg-darker  ocus:outline-none focus:text-gray-100 focus:bg-opacity-50 overflow-hidden">

   @php
      $base64Image =  'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/shikshan-logo.png')));
   @endphp
    <img class="h-14 w-32 mx-auto flex-shrink-0" src="{{ $base64Image }}" alt="">
</span> --}}
