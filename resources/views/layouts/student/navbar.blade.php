<x-navbar.navbar>

  <x-slot name="AUTH">
    @auth('student')
      @if (isset(auth()->guard('student')->user()->studentprofile->profile_photo_path))
        @if (file_exists(auth()->guard('student')->user()->studentprofile->profile_photo_path))
          <img class="inline-flex h-9 w-9 rounded-full" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path(isset(auth()->guard('student')->user()->studentprofile->profile_photo_path) ? auth()->guard('student')->user()->studentprofile->profile_photo_path : 'img/no-img.png'))) }}" src="" alt="User" />
        @endif
      @else
        <img class="inline-flex h-9 w-9 rounded-full" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/no-img.png'))) }}" alt="User" />
      @endif
      <span class="text-bold mx-2 inline-flex py-2 ">{{ isset(explode(' ', auth()->guard('student')->user()->student_name)[1]) ? explode(' ', auth()->guard('student')->user()->student_name)[1] : auth()->guard('student')->user()->student_name }}</span>
    @else
      <img class="inline-flex h-9 w-9 rounded-full" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/no-img.png'))) }}" alt="User" />
    @endauth
  </x-slot>

  <x-slot name="DROPDOWN">
    <x-navbar.mobile-dropdown-link route="student" name="Home" />
    @auth('student')
      @if (isset(auth()->guard('student')->user()->is_profile_complete))
        <x-navbar.mobile-dropdown-link route="student.view-profile" name="Profile" />
        <x-navbar.mobile-dropdown-link route="student.dashboard" name="Dashboard" />
      @else
        <x-navbar.mobile-dropdown-link route="student.profile" name="Profile" />
      @endif
      <x-navbar.mobile-dropdown-logout-link route="student.logout" name="Logout" />
    @else
      <x-navbar.mobile-dropdown-link route="student.login" name="Login" />
      <x-navbar.mobile-dropdown-link route="student.register" name="Register" />
    @endauth
  </x-slot>

  <x-slot name="NAVBAR">
    <x-navbar.mobile-link route="student" name="Student Home">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
    </x-navbar.mobile-link>
    @auth('student')
      <x-navbar.mobile-link route="student.dashboard" name="Student Dashboard">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
      </x-navbar.mobile-link>
      <x-navbar.mobile-link route="student.helpline" name="Student Helpline">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
      </x-navbar.mobile-link>
      <x-navbar.mobile-logout-link route="student.logout" name="Student Logout">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
      </x-navbar.mobile-logout-link>
    @else
      <x-navbar.mobile-link route="student.login" name="Student Login">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
      </x-navbar.mobile-link>
      <x-navbar.mobile-link route="student.register" name="Student Register">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
      </x-navbar.mobile-link>
    @endauth
  </x-slot>

</x-navbar.navbar>
