@props(['slot'=>false])
<!-- Notification panel -->
<!-- Backdrop -->
<div x-cloak x-transition:enter="transition duration-300 ease-in-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-300 ease-in-out" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-show="isNotificationsPanelOpen" @click="isNotificationsPanelOpen = false" class="fixed inset-0 z-10 bg-primary-darker" style="opacity: 0.5" aria-hidden="true"></div>
<!-- Panel -->
<section x-cloak x-transition:enter="transition duration-300 ease-in-out transform sm:duration-500" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition duration-300 ease-in-out transform sm:duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" x-ref="notificationsPanel" x-show="isNotificationsPanelOpen" @keydown.escape="isNotificationsPanelOpen = false" tabindex="-1" aria-labelledby="notificationPanelLabel" class="fixed inset-y-0 z-20 w-full max-w-xs bg-white focus:outline-none dark:bg-darker dark:text-light sm:max-w-md">
  <div class="absolute right-0 translate-x-full transform p-2">
    <!-- Close button -->
    <button @click="isNotificationsPanelOpen = false" class="rounded-md bg-red-600 p-2 text-white focus:outline-none focus:ring">
      <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
  <div class="flex h-screen flex-col" x-data="{ activeTabe: 'action' }">
    <!-- Panel header -->
    <div class="flex-shrink-0">
      <div class="flex items-center justify-between border-b px-4 pt-4 dark:border-primary-darker">
        <h2 id="notificationPanelLabel" class="pb-4 font-semibold">Notifications</h2>
      </div>
    </div>
    <!-- Panel -->
    <div class="flex-1 overflow-y-hidden pt-4 hover:overflow-y-auto">
      <div class="space-y-4">
        {{ $slot }}
      </div>
    </div>
  </div>
</section>
