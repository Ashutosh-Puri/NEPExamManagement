@props(['slot' => false, 'name' => false, 'toggle' => false])
<div class=" bg-white rounded-md dark:bg-darker" x-data="{ isOn: false }">
  <!-- Card header -->
  <div class="flex items-center justify-between p-4 border-b dark:border-primary">
    <h4 class="text-lg font-semibold text-gray-500 dark:text-light">{{ $name }}</h4>
    @if ($toggle)
      <div class="flex items-center">
        <span class="text-sm text-gray-500 dark:text-light">{{ $toggle }}</span>
        <button class="relative focus:outline-none" x-cloak @click="isOn = !isOn; updateLineChart()">
          <div class="w-12 h-6 transition rounded-full outline-none bg-primary-100 dark:bg-primary-darker">
          </div>
          <div class="absolute top-0 left-0 inline-flex items-center justify-center w-6 h-6 transition-all duration-200 ease-in-out transform scale-110 rounded-full shadow-sm" :class="{ 'translate-x-0  bg-white dark:bg-primary-100': !isOn, 'translate-x-6 bg-primary-light dark:bg-primary': isOn }">
          </div>
        </button>
      </div>
    @endif
  </div>
  <!-- Chart -->
  <div class="relative p-4 h-72">
    {{ $slot }}
  </div>
</div>
