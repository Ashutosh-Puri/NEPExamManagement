@extends("layouts.guest")
@section("guest")
  <div class="px-8 py-24 md:p-36">
    <div class="mx-auto my-4 h-full flex-1 overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-800">
      <div class="flex flex-col overflow-y-auto md:flex-row">
        <div class="h-32 md:h-auto md:w-1/2">
          <img aria-hidden="true" class="h-full w-full object-cover"src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/login-office.jpeg'))) }}"  alt="Office" />
        </div>
        <div class="flex items-center justify-center px-6 py-3 sm:p-12 md:w-1/2">
          <div class="w-full">
            <h1 class="mb-3 text-xl font-semibold text-gray-700 dark:text-gray-200">
              Student Verify Email
            </h1>
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
              {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            @if (session("status") == "verification-link-sent")
              <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
                {{ __("A new verification link has been sent to the email address you provided during registration.") }}
              </div>
            @endif

            <div class="mt-4 flex items-center justify-between">
              <form method="POST" action="{{ route("student.verification.send") }}">
                @csrf

                <div>
                  <x-primary-button>
                    {{ __("Resend Verification Email") }}
                  </x-primary-button>
                </div>
              </form>

              <form method="POST" action="{{ route("student.logout") }}">
                @csrf
                <x-primary-button>
                  {{ __("Log Out") }}
                </x-primary-button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
