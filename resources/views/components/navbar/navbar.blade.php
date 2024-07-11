@props(['NAVBAR' => false, 'DROPDOWN' => false, 'AUTH' => false])
<!-- Navbar -->
<header class="relative bg-white dark:bg-darker">
  <div class="flex items-center justify-between border-b p-2 dark:border-primary-darker">
    <!-- Mobile menu button -->
    <button @click="isMobileMainMenuOpen = !isMobileMainMenuOpen" class="rounded-md bg-primary-50 p-1 text-primary-lighter transition-colors duration-200 hover:bg-primary-100 hover:text-primary focus:outline-none focus:ring dark:bg-dark dark:hover:bg-primary-dark dark:hover:text-light md:hidden">
      <span class="sr-only">Open main manu</span>
      <span aria-hidden="true">
        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </span>
    </button>

    <!-- Sidebar button -->
    <button x-cloak @click="isSidebarExpanded = !isSidebarExpanded" class="hidden rounded-md bg-primary-50 p-1 text-primary-lighter transition-colors duration-200 hover:bg-primary-100 hover:text-primary focus:outline-none focus:ring dark:bg-dark dark:hover:bg-primary-dark dark:hover:text-light md:block">
      <svg viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 transform" :class="isSidebarExpanded ? 'rotate-180' : 'rotate-0'">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <line x1="4" y1="6" x2="14" y2="6" />
        <line x1="4" y1="18" x2="14" y2="18" />
        <path d="M4 12h17l-3 -3m0 6l3 -3" />
      </svg>
    </button>

    <!-- Brand -->
    <a href="{{ url('/') }}" class="text-center text-sm text-primary-dark dark:text-light md:text-md md:font-bold">
      {{ config('app.name') }}
    </a>
    {{-- <span class="text-center text-sm text-primary-dark dark:text-light md:text-md md:font-bold">
      {{ config("app.name") }}
    </span> --}}

    <!-- Mobile sub menu button -->
    <button @click="isMobileSubMenuOpen = !isMobileSubMenuOpen" class="rounded-md bg-primary-50 p-1 text-primary-lighter transition-colors duration-200 hover:bg-primary-100 hover:text-primary focus:outline-none focus:ring dark:bg-dark dark:hover:bg-primary-dark dark:hover:text-light md:hidden">
      <span class="sr-only">Open sub manu</span>
      <span aria-hidden="true">
        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
        </svg>
      </span>
    </button>

    <!-- Desktop Right buttons -->
    <nav aria-label="Secondary" class="hidden space-x-2 md:flex md:items-center">
      <!-- Desktop Toggle dark theme button -->
      <button aria-hidden="true" class="relative  focus:outline-none" x-cloak @click="toggleTheme">
        <div class="h-6 w-12 rounded-full bg-primary-100 outline-none transition dark:bg-primary-lighter">
        </div>
        <div class="absolute left-0 top-0 inline-flex h-6 w-6 scale-110 transform items-center justify-center rounded-full shadow-sm transition-all duration-150" :class="{ 'translate-x-0 -translate-y-px  bg-white text-primary-dark': !isDark, 'translate-x-6 text-primary-100 bg-primary-darker': isDark }">
          <svg x-show="!isDark" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
          </svg>
          <svg x-show="isDark" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
          </svg>
        </div>
      </button>
      <!-- Notification button -->
      <button x-cloak @click="openNotificationsPanel" class="p-2 transition-colors duration-200 rounded-full text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark focus:outline-none focus:bg-primary-100 dark:focus:bg-primary-dark focus:ring-primary-darker">
        <span class="sr-only">Open Notification panel</span>
        <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
      </button>

      <!-- Desktop Settings button -->
      <button x-cloak @click="openSettingsPanel" class="rounded-full bg-primary-50 p-2 text-primary-lighter transition-colors duration-200 hover:bg-primary-100 hover:text-primary focus:bg-primary-100 focus:outline-none focus:ring-primary-darker dark:bg-dark dark:hover:bg-primary-dark dark:hover:text-light dark:focus:bg-primary-dark">
        <span class="sr-only">Open settings panel</span>
        <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
      </button>

      <!--Desktop User avatar button -->
      <div x-cloak class="relative" x-data="{ open: false }">
        <button @click="open = !open; $nextTick(() => { if(open){ $refs.userMenu.focus() } })" type="button" aria-haspopup="true" :aria-expanded="open ? 'true' : 'false'" class="transition-opacity duration-200 focus:outline-none dark:opacity-75 dark:hover:opacity-100 dark:focus:opacity-100 ">
          <span class="sr-only">User menu</span>
          <span class="inline-flex">
            {{ $AUTH }}
            <span class="mx-1 py-2">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-flex h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
              </svg>
            </span>
          </span>
        </button>

        <!-- Desktop User dropdown menu -->
        <div @click.away="open = false" x-show="open" x-ref="userMenu" x-transition:enter="transition-all transform ease-out" x-transition:enter-start="translate-y-1/2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition-all transform ease-in" @keydown.escape="open = false" class="absolute right-0 top-12 w-48 rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-dark z-50" tabindex="-1" role="menu" aria-orientation="vertical" aria-label="User menu">
          {{ $DROPDOWN }}
        </div>
      </div>
    </nav>

    <!-- Mobile sub menu -->
    <nav x-transition:enter="transition duration-200 ease-in-out transform sm:duration-500"x-transition:enter-start="-translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition duration-300 ease-in-out transform sm:duration-500" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-full opacity-0" x-show="isMobileSubMenuOpen" @click.away="isMobileSubMenuOpen = false" class="z-10 absolute inset-x-4 top-16 flex items-center rounded-md bg-white p-4 shadow-lg dark:bg-darker md:hidden" aria-label="Secondary">
      <div class="space-x-2">
        <!-- Mobile Toggle dark theme button -->
        <button aria-hidden="true" class="relative focus:outline-none" x-cloak @click="toggleTheme">
          <div class="h-6 w-12 rounded-full bg-primary-100 outline-none transition dark:bg-primary-lighter"></div>
          <div class="absolute left-0 top-0 inline-flex h-6 w-6 scale-110 transform items-center justify-center rounded-full shadow-sm transition-all duration-200" :class="{ 'translate-x-0 -translate-y-px  bg-white text-primary-dark': !isDark, 'translate-x-6 text-primary-100 bg-primary-darker': isDark }">
            <svg x-show="!isDark" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <svg x-show="isDark" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
            </svg>
          </div>
        </button>
        <!-- Notification button -->
        <button x-cloak @click="openNotificationsPanel(); $nextTick(() => { isMobileSubMenuOpen = false })" class="p-2 transition-colors duration-200 rounded-full text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark focus:outline-none focus:bg-primary-100 dark:focus:bg-primary-dark focus:ring-primary-darker">
          <span class="sr-only">Open notifications panel</span>
          <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
        </button>

        <!-- Mobile Settings button -->
        <button x-cloak @click="openSettingsPanel(); $nextTick(() => { isMobileSubMenuOpen = false })" class="rounded-full bg-primary-50 p-2 text-primary-lighter transition-colors duration-200 hover:bg-primary-100 hover:text-primary focus:bg-primary-100 focus:outline-none focus:ring-primary-darker dark:bg-dark dark:hover:bg-primary-dark dark:hover:text-light dark:focus:bg-primary-dark">
          <span class="sr-only">Open settings panel</span>
          <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </button>
      </div>

      <!-- Mobile User avatar button -->
      <div x-cloak class="relative ml-auto" x-data="{ open: false }">
        <button @click="open = !open" type="button" aria-haspopup="true" :aria-expanded="open ? 'true' : 'false'" class="duration-200dark:opacity-75 mx-auto block transition-opacity focus:outline-none dark:hover:opacity-100 dark:focus:opacity-100">
          <span class="sr-only">User menu</span>
          <span class="inline-flex">
            {{ $AUTH }}
            <span class="mx-1 py-2">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-flex h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
              </svg>
            </span>
          </span>
        </button>
        <!-- Mobile User dropdown menu -->
        <div @click.away="open = false" x-show="open" x-transition:enter="transition-all transform ease-out" x-transition:enter-start="translate-y-1/2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition-all transform ease-in" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-1/2 opacity-0" @click.away="open = false" role="menu" aria-orientation="vertical" aria-label="User menu">
          {{ $DROPDOWN }}
        </div>
      </div>
    </nav>
  </div>
  <!-- Mobile Sidebar -->
  <div class="border-b dark:border-primary-darker md:hidden" x-show="isMobileMainMenuOpen" @click.away="isMobileMainMenuOpen = false">
    <nav aria-label="Main" class="space-y-2 px-2 py-4">
      {{ $NAVBAR }}
    </nav>
  </div>

</header>
