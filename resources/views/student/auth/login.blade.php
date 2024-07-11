@extends("layouts.student")
@section("student")
  <div class="p-8">
    <div class="mx-auto my-6 h-full max-w-4xl flex-1 overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-800">
      <div class="flex flex-col overflow-y-auto md:flex-row">
        <div class="h-32 md:h-auto md:w-1/2">
          <img aria-hidden="true" class="h-full w-full object-cover " src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/login-office.jpeg'))) }}"  alt="Office" />
        </div>
        <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
          <div class="w-full">
            <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
             Student Login
            </h1>
             <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
        
            <form method="POST" action="{{ route('student.login') }}">
                @csrf
                <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" /><x-required/>
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        
            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" /><x-required/>
                <x-text-input id="password" class="block mt-1 w-full"  type="password"  name="password"  required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            
             <!-- Remember Me -->
             <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <x-input-checkbox id="remember_me" type="checkbox"  name="remember"/>
                    <x-input-label  class="mx-2" for="remember_me" :value="__('Remember me')" />
                </label>
            </div>
            <div class="flex items-center text-center justify-end mt-4">
                <x-primary-button class="w-full text-center">
                   <span class="mx-auto text-center">{{ __('Log in') }}</span> 
                </x-primary-button>
            </div>
            <hr class="my-10" />
            <p class="mt-1">
                @if (Route::has("student.password.request"))
                    <a  wire:navigate  class="text-sm font-medium text-primary hover:border-b-2  border-primary dark:text-primary" href="{{ route('student.password.request') }}"> {{ __('Forgot Your Password ?') }}</a>
                @endif
            </p>
            <p class="mt-1">
                <a wire:navigate class="text-sm font-medium text-primary hover:border-b-2 border-primary  dark:text-primary" href="{{ route('student.register') }}">{{ __('Create New Account') }}</a>
            </p>
            </form>
        </div>
      </div>
    </div>
  </div>
@endsection
