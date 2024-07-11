<div>
  <section>
    {{-- Header --}}
    <section>
      <div class="bg-white dark:text-white dark:bg-slate-800  ring-1 ring-slate-900/5 grid grid-cols-1 justify-between gap-2 p-3 lg:grid-cols-3">
        <div class="order-1 flex-1 text-center text-2xl font-semibold">
          @if ($college)
            {{ $college->college_name_marathi }}
          @endif
        </div>
        <div class="order-first flex-1 md:order-2">
          <img class="w-26 m-auto h-24 animate-pulse rounded" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/shikshan-logo.png'))) }}" alt="Shikshan Logo" />
        </div>
        <div class="order-last flex-1 text-center text-2xl font-semibold">
          @if ($college)
            {{ $college->college_name }}
          @endif
        </div>
      </div>
    </section>
    {{-- Navbar --}}
    <section>
      <div x-data="{ open: false }" class="sticky top-0 z-20 mx-auto !min-h-10 flex max-w-full flex-col bg-primary-dark px-4 py-4 text-white shadow-lg md:flex-row md:items-center md:justify-between md:py-2 lg-py-2">
        <button class="focus:shadow-outline absolute right-0 top-0 mr-5 rounded-lg focus:outline-none md:hidden" @click="open = !open">
          <svg fill="currentColor" viewBox="0 0 20 20" class="h-10 w-10">
            <path x-show="!open" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            <path x-show="open" fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <nav :class="{ 'flex mt-6': open, 'hidden': !open }" class="hidden flex-grow flex-col  pb-4 md:flex md:flex-row md:justify-end md:pb-0">
          <div x-cloak @click.away="open = false" class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="dark-mode:bg-transparent dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:focus:bg-gray-600 dark-mode:hover:bg-gray-600 focus:shadow-outline mt-2 flex w-full flex-row items-center rounded-lg bg-transparent px-4 py-2 text-left text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:ml-4 md:mt-0 md:inline md:w-auto">
              <span>Student's</span>
              <svg fill="currentColor" viewBox="0 0 20 20" :class="{ 'rotate-180': open, 'rotate-0': !open }" class="ml-1 mt-1 inline h-4 w-4 transform transition-transform duration-200 md:-mt-1">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
              </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="relative right-0 mt-2 w-full origin-top-right rounded-md shadow-lg md:absolute md:w-48">
              <div class="dark-mode:bg-gray-800 rounded-md bg-primary-darker px-1 py-2 shadow">
                <a wire:navigate class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0" href="{{ route('student') }}">
                  Home
                </a>
                @auth('student')
                  <a wire:navigate class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0" href="{{ route('student.dashboard') }}">
                    Dashboard
                  </a>
                  <form method="POST" action="{{ route('student.logout') }}">
                    @csrf
                    <span onclick="event.preventDefault(); this.closest('form').submit();" class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0">
                      Logout
                    </span>
                  </form>
                @else
                  <a wire:navigate class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0" href="{{ route('student.login') }}">
                    Login
                  </a>
                  <a wire:navigate class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0" href="{{ route('student.register') }}">
                    Register
                  </a>
                @endauth
              </div>
            </div>
          </div>
          <div x-cloak @click.away="open = false" class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="dark-mode:bg-transparent dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:focus:bg-gray-600 dark-mode:hover:bg-gray-600 focus:shadow-outline mt-2 flex w-full flex-row items-center rounded-lg bg-transparent px-4 py-2 text-left text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:ml-4 md:mt-0 md:inline md:w-auto">
              <span>Department's</span>
              <svg fill="currentColor" viewBox="0 0 20 20" :class="{ 'rotate-180': open, 'rotate-0': !open }" class="ml-1 mt-1 inline h-4 w-4 transform transition-transform duration-200 md:-mt-1">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
              </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="relative right-0 mt-2 w-full origin-top-right rounded-md shadow-lg md:absolute md:w-48">
              <div class="dark-mode:bg-gray-800 rounded-md bg-primary-darker px-1 py-2 shadow">
                {{-- <a wire:navigate class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0" href="{{ route('faculty') }}">
                  Home
                </a> --}}
                @auth('faculty')
                  <a wire:navigate class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0" href="{{ route('faculty.dashboard') }}">Dashboard</a>
                  <form method="POST" action="{{ route('faculty.logout') }}">
                    @csrf
                    <span onclick="event.preventDefault(); this.closest('form').submit();" class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0">
                      Logout
                    </span>
                  </form>
                @else
                  <a wire:navigate class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0" href="{{ route('faculty.login') }}">Login</a>
                @endauth
              </div>
            </div>
          </div>
          <div x-cloak @click.away="open = false" class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="dark-mode:bg-transparent dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:focus:bg-gray-600 dark-mode:hover:bg-gray-600 focus:shadow-outline mt-2 flex w-full flex-row items-center rounded-lg bg-transparent px-4 py-2 text-left text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:ml-4 md:mt-0 md:inline md:w-auto">
              <span>Examination's</span>
              <svg fill="currentColor" viewBox="0 0 20 20" :class="{ 'rotate-180': open, 'rotate-0': !open }" class="ml-1 mt-1 inline h-4 w-4 transform transition-transform duration-200 md:-mt-1">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
              </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="relative right-0 mt-2 w-full origin-top-right rounded-md shadow-lg md:absolute md:w-48">
              <div class="dark-mode:bg-gray-800 rounded-md bg-primary-darker px-1 py-2 shadow">
                {{-- <a wire:navigate class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0" href="{{ route('user') }}">
                  Home
                </a> --}}
                @auth('user')
                  <a wire:navigate class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0" href="{{ route('user.dashboard') }}">
                    Dashboard
                  </a>
                  <form method="POST" action="{{ route('user.logout') }}">
                    @csrf
                    <span onclick="event.preventDefault(); this.closest('form').submit();" class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0">
                      Logout
                    </span>
                  </form>
                @else
                  <a wire:navigate class="dark-mode:bg-transparent dark-mode:hover:bg-gray-600 dark-mode:focus:bg-gray-600 dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:text-gray-200 focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-lg font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:mt-0" href="{{ route('user.login') }}">
                    Login
                  </a>
                @endauth
              </div>
            </div>
          </div>
          @auth('user')
            <a wire:navigate href="{{ route('user.dashboard') }}" class="right-0 px-6 py-2 sm:block dark-mode:bg-transparent dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:focus:bg-gray-600 dark-mode:hover:bg-gray-600 focus:shadow-outline mt-2 flex w-full flex-row items-center rounded-lg bg-transparent   font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:ml-4 md:mt-0 md:inline md:w-auto text-white-700 text-lg">Dashboard</a>
          @else
            <a wire:navigate href="{{ route('user.login') }}" class="right-0 px-6 py-2 sm:block dark-mode:bg-transparent dark-mode:focus:text-white dark-mode:hover:text-white dark-mode:focus:bg-gray-600 dark-mode:hover:bg-gray-600 focus:shadow-outline mt-2 flex w-full flex-row items-center rounded-lg bg-transparent   font-semibold hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none md:ml-4 md:mt-0 md:inline md:w-auto text-white-700 text-lg">Login</a>
          @endauth
        </nav>
      </div>
    </section>
    {{-- Content --}}
    <section class="p-3">
      <div class=" grid grid-cols-1 gap-2  lg:grid-cols-3">
        <!-- Principal card -->
        <div class="flex items-center justify-between rounded-md dark:bg-darker dark:border-primary-darker border">
          <div class="min-h-full w-full rounded-lg bg-white dark:bg-slate-800  ring-1 ring-slate-900/5 px-6 py-5 shadow-xl ">
            <div class="flex justify-center">
              <span>
                <img style=" height:125px; width:125px;" class=" rounded-full object-cover" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/principal-photo.jpg'))) }}" alt="Photo">
              </span>
            </div>
            <h2 class="mt-5 text-base font-medium tracking-tight text-slate-900 dark:text-white">
              Prof. (Dr.) Arun Gaikwad Principal
              <hr>
            </h2>
            <x-read-more limit="115">
              I am very happy to present the profile of our college. Established with the aim to spread knowledge unto the last, we have tried to be the lighthouse for the rural youth. The college started with the generous donations of those after whom the three faculties have been named and those to whom we are indebted for the huge campus, and also with the donations of coolies and workers. We have never lost sight of the grass root level, but we have always aspired for wider horizons. College has developed infrastructure necessary for an overall development of the students – classrooms, laboratories, library, gymnasium, a big playground, auditorium, audio-visual aids etc. At the same time, we have been educating first generation learners and we have also been equipping our students with the caliber required for a global competition. With the vision of ex-Principal M.V. Kaundinya and the ex-Chairman late Shri Omkarnathji Malpnai, the college has striven for academic excellence and also established firm linkages with the society around. In our rural development projects, N.S.S. activities, Learn to Earn scheme etc. we express our social commitment. We look forward to higher levels of achievement for the students and for the college. We are sure that we can keep pace with the changing times.
            </x-read-more>
          </div>
        </div>
        <!-- Sub Principal  card -->
        <div class="flex items-center justify-between rounded-md dark:bg-darker dark:border-primary-darker border">
          <div class="min-h-full w-full rounded-lg bg-white dark:bg-slate-800  ring-1 ring-slate-900/5  px-6 py-5 shadow-xl ">
            <div class="flex justify-center">
              <span>
                <img style=" height:125px; width:125px;" class="rounded-full object-cover" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/r-s-laddha.jpg'))) }}" alt="Photo">
              </span>
            </div>
            <h2 class="mt-5 text-base font-medium tracking-tight text-slate-900 dark:text-white">
              Dr.Rajendra Laddha Controller of Examination
              <hr>
            </h2>
            <x-read-more limit="160">
              {{-- I am very happy to present the profile of our college. Established with the aim to spread knowledge unto the last, we have tried to be the lighthouse for the rural youth. The college started with the generous donations of those after whom the three faculties have been named and those to whom we are indebted for the huge campus, and also with the donations of coolies and workers. We have never lost sight of the grass root level, but we have always aspired for wider horizons. College has developed infrastructure necessary for an overall development of the students – classrooms, laboratories, library, gymnasium, a big playground, auditorium, audio-visual aids etc. At the same time, we have been educating first generation learners and we have also been equipping our students with the caliber required for a global competition. With the vision of ex-Principal M.V. Kaundinya and the ex-Chairman late Shri Omkarnathji Malpnai, the college has striven for academic excellence and also established firm linkages with the society around. In our rural development projects, N.S.S. activities, Learn to Earn scheme etc. we express our social commitment. We look forward to higher levels of achievement for the students and for the college. We are sure that we can keep pace with the changing times. --}}
            </x-read-more>
          </div>
        </div>

        <!-- Recent Updates card -->
        <div class="flex items-center justify-between rounded-md dark:bg-darker dark:border-primary-darker border">
          <div class="min-h-full w-full rounded-lg bg-white dark:bg-slate-800  ring-1 ring-slate-900/5  px-6 py-5 shadow-xl ">
            <h2 class="mt-5 text-base font-medium tracking-tight text-slate-900 dark:text-white">
              Recent Updates
              <hr>
            </h2>
            <x-accordion.accordion tab="0">
              @foreach ($guest_notices as $key => $notice)
                <x-accordion.accordion-item tab="{{ $key + 1 }}" title="{{ $notice->title }}">{{ $notice->description }}</x-accordion.accordion-item>
              @endforeach
            </x-accordion.accordion>
            {{ $guest_notices->links() }}
          </div>
        </div>
      </div>
    </section>
    {{-- Footer --}}
    <section>
      <div class="block bottom-0 relative dark:bg-darker ">
        <footer class="min-h-full w-full  bg-white dark:bg-slate-800  ring-1 ring-slate-900/5   pt-2 shadow-xl ">

          <div class="grid grid-cols-2 gap-8 px-10 py-3 lg:py-4 md:grid-cols-4">
            <div>
              <h2 class="mb-6 text-sm font-semibold text-primary uppercase dark:text-primary">Student Section</h2>
              <ul class="text-gray-500 dark:text-gray-400 font-medium">
                {{-- <li class="mb-2">
                  <a href="{{ route('student') }}" class=" hover:underline hover:text-primary">Home</a>
                </li> --}}
                <li class="mb-2">
                  <a href="{{ route('student.login') }}" class="hover:underline hover:text-primary">Login</a>
                </li>
                <li class="mb-2">
                  <a href="{{ route('student.register') }}" class="hover:underline hover:text-primary">Register</a>
                </li>
                <li class="mb-2">
                  <a href="{{ route('student.helpline') }}" class="hover:underline hover:text-primary">Helpline</a>
                </li>
              </ul>
            </div>
            <div>
              <h2 class="mb-6 text-sm font-semibold text-primary uppercase dark:text-primary">Department Section</h2>
              <ul class="text-gray-500 dark:text-gray-400 font-medium">
                {{-- <li class="mb-2">
                  <a href="{{ route('faculty') }}" class="hover:underline hover:text-primary">Home</a>
                </li> --}}
                <li class="mb-2">
                  <a href="{{ route('faculty.login') }}" class="hover:underline hover:text-primary">Login</a>
                </li>
              </ul>
            </div>
            <div>
              <h2 class="mb-6 text-sm font-semibold text-primary uppercase dark:text-primary">Examination Section</h2>
              <ul class="text-gray-500 dark:text-gray-400 font-medium">
                {{-- <li class="mb-2">
                  <a href="{{ route('user') }}" class="hover:underline hover:text-primary">Home</a>
                </li> --}}
                <li class="mb-2">
                  <a href="{{ route('user.login') }}" class="hover:underline hover:text-primary">Login</a>
                </li>
              </ul>
            </div>
            <div>
              <h2 class="mb-6 text-sm font-semibold text-primary uppercase dark:text-primary">Other</h2>
              <ul class="text-gray-500 dark:text-gray-400 font-medium">
                <li class="mb-2">
                  <a href="#" class="hover:underline hover:text-primary">Contact Us</a>
                </li>
                <li class="mb-2">
                  <a href="#" class="hover:underline hover:text-primary">About Us</a>
                </li>
                <li class="mb-2">
                  <a href="#" class="hover:underline hover:text-primary">Terms &amp; Conditions</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="px-14 py-4 bg-gray-100 dark:bg-gray-700 md:flex md:items-center md:justify-between w-full ">
            <span class="text-sm text-gray-500 dark:text-gray-300  text-balance text-justify mx-auto">© 2024 <a href="{{ url('/') }}">
                @if ($college)
                  {{ $college->college_name }}
                @endif
              </a> All Rights Reserved.
            </span>
            {{-- <div class="flex mt-4 sm:justify-center md:mt-0 space-x-5 rtl:space-x-reverse">
              <a href="#" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 8 19">
                  <path fill-rule="evenodd" d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z" clip-rule="evenodd" />
                </svg>
                <span class="sr-only">Facebook page</span>
              </a>
              <a href="#" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 17">
                  <path fill-rule="evenodd" d="M20 1.892a8.178 8.178 0 0 1-2.355.635 4.074 4.074 0 0 0 1.8-2.235 8.344 8.344 0 0 1-2.605.98A4.13 4.13 0 0 0 13.85 0a4.068 4.068 0 0 0-4.1 4.038 4 4 0 0 0 .105.919A11.705 11.705 0 0 1 1.4.734a4.006 4.006 0 0 0 1.268 5.392 4.165 4.165 0 0 1-1.859-.5v.05A4.057 4.057 0 0 0 4.1 9.635a4.19 4.19 0 0 1-1.856.07 4.108 4.108 0 0 0 3.831 2.807A8.36 8.36 0 0 1 0 14.184 11.732 11.732 0 0 0 6.291 16 11.502 11.502 0 0 0 17.964 4.5c0-.177 0-.35-.012-.523A8.143 8.143 0 0 0 20 1.892Z" clip-rule="evenodd" />
                </svg>
                <span class="sr-only">Twitter page</span>
              </a>
            </div> --}}
          </div>
        </footer>
      </div>
    </section>
  </section>
</div>
