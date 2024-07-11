@extends('layouts.student')
@section('student')
<div class="md:p-24 p-8">
    <div class="mx-auto my-4 h-full  flex-1 overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-800">
      <div class="flex flex-col overflow-y-auto md:flex-row">
        <div class="h-32 md:h-auto md:w-1/2">
          <img aria-hidden="true" class="h-full w-full object-cover " src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/login-office.jpeg'))) }}"  alt="Office" />
        </div>
        <div class="flex items-center justify-center px-6 py-3 sm:p-12 md:w-full">
          <div class="w-full">
            <h1 class="mb-3 text-xl font-semibold text-gray-700 dark:text-gray-200">
             Student Password Reset
            </h1>
            <form method="POST" action="{{ route('student.password.store') }}">
                @csrf
            
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
            
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            
                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            
                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
                
                <div class="flex items-center text-center justify-end mt-4">
                    <x-primary-button class="w-full text-center">
                    <span class="mx-auto text-center">{{ __('Reset Password') }}</span> 
                    </x-primary-button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
@endsection
