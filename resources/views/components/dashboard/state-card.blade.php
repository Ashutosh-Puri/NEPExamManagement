@props(['slot' => false,'groth'=>false,'title'=>false,'count'=>false])
<div class="flex items-center justify-between p-4 bg-white rounded-md dark:bg-darker border border-primary">
  <div>
    <h6 class="text-xs  leading-none tracking-wider  uppercase dark:text-primary-light text-primary font-bold">
     {{ $title }}
    </h6>
    <span class="text-xl font-semibold">{{ $count }}</span>
    @if ($groth)
        <span class="inline-block px-2 py-px ml-2 text-xs text-green-500 bg-green-100 rounded-md ">
        {{ $groth  }}
        </span>  
    @endif
  </div>
  <div>
    <span>
      <svg class="w-12 h-12 text-primary dark:text-primary-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        {{ $slot }}
      </svg>
    </span>
  </div>
</div>
