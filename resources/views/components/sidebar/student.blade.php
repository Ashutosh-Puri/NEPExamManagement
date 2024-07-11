@if (isset($header))
<header class="bg-white shadow-2xl mx-2 rounded-md">
    <div class="max-w-7xl mx-auto py-4 px-4   ">
        {{ $header }}
       
    </div>
</header>

@endif
<div>    
        {{ $slot }}
 
</div>