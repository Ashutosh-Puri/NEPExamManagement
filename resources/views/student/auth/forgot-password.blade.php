@extends("layouts.student")
@section("student")
  <div class="flex min-h-screen items-center bg-gray-50 p-6 dark:bg-gray-900">
    <div class="mx-auto h-full max-w-4xl flex-1 overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-800">
      <div class="flex flex-col overflow-y-auto md:flex-row">
        <div class="h-32 md:h-auto md:w-1/2">
          <img aria-hidden="true" class="h-full w-full object-cover" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/forgot-password-office.jpeg'))) }}" alt="Office" />
        </div>
        <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
          <div class="w-full">
            <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
             Student Forgot Password
            </h1>
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <form method="POST" action="{{ route('student.password.email') }}">
                @csrf
            
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center text-center justify-end mt-4">
                    <x-primary-button class="w-full text-center">
                       <span class="mx-auto text-center">{{ __('Email Password Reset Link') }}</span> 
                    </x-primary-button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
