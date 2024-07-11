@extends("layouts.student")
@section("student")
<div class="p-4">
    <div class="mx-auto my-14 xm:my-2 h-full  flex-1 overflow-hidden rounded-lg bg-white shadow-xl dark:bg-gray-800">
      <div class="flex flex-col overflow-y-auto md:flex-row">
        {{-- <div class="h-32 md:h-auto md:w-1/2">
          <img aria-hidden="true" class="h-full w-full object-cover " src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/login-office.jpeg'))) }}"   alt="Office" />
        </div> --}}
        <div class="flex items-center justify-center px-6 py-3 sm:p-12 w-full">
          <div class="w-full">
            <h1 class="mb-3 text-xl font-semibold text-gray-700 dark:text-gray-200">
             Student Register
            </h1>
            <form method="POST" action="{{ route('student.register') }}">
                @csrf
    
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 ">
                    <!--Last Name -->
                    <div class="mt-1">
                        <x-input-label for="last_name" :value="__('Last Name')" /> <x-required/>
                        <x-text-input placeholder="Enter Last Name" id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" />
                        <x-input-error :messages="$errors->get('last_name')" class="mt-1" />
                    </div>
                    <!--First Name -->
                    <div class="mt-1">
                        <x-input-label for="first_name" :value="__('First Name')" /><x-required/>
                        <x-text-input placeholder="Enter First Name" id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
                        <x-input-error :messages="$errors->get('first_name')" class="mt-1" />
                    </div>
    
                    <!--Middle Name -->
                    <div class="mt-1"> 
                        <x-input-label for="middle_name" :value="__('Middle Name')" /><x-required/>
                        <x-text-input placeholder="Enter Middle Name" id="middle_name" class="block mt-1 w-full" type="text" name="middle_name" :value="old('middle_name')" required autofocus autocomplete="middle_name" />
                        <x-input-error :messages="$errors->get('middle_name')" class="mt-1" />
                    </div>

                    <!--Member ID -->
                    <div class="mt-1">
                        <x-input-label for="member_id" :value="__('Member ID')" /><x-required/>
                        <x-text-input placeholder="Enter Member ID As Per Your I-CARD " id="member_id" class="block mt-1 w-full" type="number" name="member_id" :value="old('member_id')" required autofocus autocomplete="member_id" />
                        <x-input-error :messages="$errors->get('member_id')" class="mt-1" />
                    </div>
    
                    <!--Mobile Number -->
                    <div class="mt-1">
                        <x-input-label for="mobile_no" :value="__('Mobile Number')" /><x-required/>
                        <x-text-input placeholder="Enter Mobile Number" id="mobile_no" class="block mt-1 w-full" type="number" name="mobile_no" :value="old('mobile_no')" required autofocus autocomplete="mobile_no" />
                        <x-input-error :messages="$errors->get('mobile_no')" class="mt-1" />
                    </div>

                    <!--Mother Name -->
                    <div class="mt-1">
                        <x-input-label for="mother_name" :value="__('Mother Name')" /><x-required/>
                        <x-text-input placeholder="Enter Mother Name" id="mother_name" class="block mt-1 w-full" type="text" name="mother_name" :value="old('mother_name')" required autofocus autocomplete="mother_name" />
                        <x-input-error :messages="$errors->get('mother_name')" class="mt-1" />
                    </div>
                
                    <!-- Email -->
                    <div class="mt-1">
                        <x-input-label for="email" :value="__('Email')" /><x-required/>
                        <x-text-input placeholder="Enter Valid Email" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                
                    <!-- Password -->
                    <div class="mt-1">
                        <x-input-label for="password" :value="__('Password')" /><x-required/>
                        <x-text-input placeholder="Enter Password" id="password" class="block mt-1 w-full"  type="password"  name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>
                
                    <!-- Confirm Password -->
                    <div class="mt-1">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" /><x-required/>
                        <x-text-input placeholder="Enter Confirm Password" id="password_confirmation" class="block mt-1 w-full"  type="password"  name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>
                </div>
                <div class="flex items-center text-center justify-end mt-4">
                    <x-primary-button class="w-full text-center">
                       <span class="mx-auto text-center">{{ __('Register') }}</span> 
                    </x-primary-button>
                </div>
                <hr class="my-5" />
                <p class="mt-1">
                    <a wire:navigate class="text-sm font-medium text-primary hover:border-b-2  border-primary dark:text-primary" href="{{ route('student.login') }}">{{ __('Already have an account ? Login') }}</a>
                </p>
            </form>
        </div>
      </div>
    </div>
  </div>
@endsection
