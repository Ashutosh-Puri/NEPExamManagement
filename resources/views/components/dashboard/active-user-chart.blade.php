@props(['slot' => false, 'name' => false, 'lable' => false, 'count' => false, 'id' => false])
<div class= " bg-white rounded-md dark:bg-darker">
  <!-- Card header -->
  <div class="p-4 border-b dark:border-primary">
    <h4 class="text-lg font-semibold text-gray-500 dark:text-light">{{ $name }}</h4>
  </div>
  <p class="p-4">
    <span class="text-2xl font-medium text-gray-500 dark:text-light" id="{{ $id }}">{{ $count }}</span>
    <span class="text-sm font-medium text-gray-500 dark:text-primary">{{ $lable }}</span>
  </p>
  <!-- Chart -->
  <div class="relative p-4 ">
    {{ $slot }}
  </div>
</div>
