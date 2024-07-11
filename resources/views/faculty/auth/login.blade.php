@extends('layouts.faculty')
@section('faculty')

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 px-2">
    <div>
        <a href="/">
            <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
        </a>
    </div>

    <div class="w-full sm:max-w-md mt-2 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">

        <div>
            <h2 class="uppercase text-center w-full  my-2 ">Faculty Login</h2>
        </div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        
        <form method="POST" action="{{ route('faculty.login') }}">
            @csrf
        
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" /> <x-required/>
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        
            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" /><x-required/>
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        
            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember" class="inline-flex items-center">
                    <x-input-checkbox id="remember" type="checkbox"  name="remember"/>
                    <x-input-label for="remember" class="mx-2" :value="__('Remember me')" />
                </label>
            </div>
        
            <div class="flex items-center justify-end mt-4">
                @if (Route::has('faculty.password.request'))
                    <a class="text-sm font-medium text-primary hover:border-b-2  border-primary dark:text-primary" href="{{ route('faculty.password.request') }}">
                        {{ __('Forgot Your Password ?') }}
                    </a>
                @endif
        
                <x-primary-button class="ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection

