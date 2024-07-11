@props(['slot' => false, 'header' => false, 'footer' => false])
<!-- Sidebar -->
<aside class="hidden h-full flex-col bg-white transition-all dark:border-primary-darker dark:bg-darker md:flex" :class="isSidebarExpanded ? 'w-64' : 'w-20'">
  <div class="flex h-full flex-col dark:border-primary-darker">
    <!-- Sidebar Header -->
    <x-sidebar.logo />
    {{ $header }}
    <!-- Sidebar links -->
    <nav aria-label="Main" :class="isSidebarExpanded ? 'scrollbar-md' : 'scrollbar-xs'" class=" flex-1 items-center space-y-1 overflow-x-hidden overflow-y-hidden border-r p-2 hover:overflow-y-auto dark:border-primary-darker dark:bg-darker">
      {{ $slot }}
    </nav>
    <!-- Sidebar footer -->
    @if ($footer)
      <div class="flex-shrink-0 space-y-2 border-r px-2 py-4 dark:border-primary-darker dark:bg-darker">
        {{ $footer }}
      </div>
    @endif
</aside>
