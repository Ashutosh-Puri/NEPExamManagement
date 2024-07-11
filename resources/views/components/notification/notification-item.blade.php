@props(['id' => false, 'name' => false, 'time' => false, 'status' => false ,'last'=>false])
<span {{ $attributes->merge(['class' => 'block']) }}>
  <div class="flex space-x-4 px-4">
    <div class="relative flex-shrink-0">
      <span class="inline-block overflow-visible rounded-full bg-primary-50 p-2 text-primary-light dark:bg-primary-darker">
        <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
      </span>
      @if (!$last)
        <div class="absolute left-1/2 -ml-px -mt-3 h-4 bg-primary-50 p-px dark:bg-primary-darker"></div>
      @endif
    </div>
    <div class="flex-1 overflow-hidden">

      @switch($status)
        @case($status == 'success')
          <h5 class="text-sm font-semibold text-green-600">{{ $name }}</h5>
          <span class="text-sm font-normal text-green-600">{{ $time }} </span>
        @break

        @case($status == 'error')
          <h5 class="text-sm font-semibold text-red-600">{{ $name }}</h5>
          <span class="text-sm font-normal text-red-600">{{ $time }} </span>
        @break

        @case($status == 'info')
          <h5 class="text-sm font-semibold text-cyan-600">{{ $name }}</h5>
          <span class="text-sm font-normal text-cyan-600">{{ $time }} </span>
        @break

        @case($status == 'warning')
          <h5 class="text-sm font-semibold text-yellow-600">{{ $name }}</h5>
          <span class="text-sm font-normal text-yellow-600">{{ $time }} </span>
        @break
      @endswitch
    </div>
    {{ $slot }}
  </div>
</span>
