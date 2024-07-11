<x-card-collapsible heading="Faculty Registration">
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="prefix" :value="__('Prefix')" />
            <x-input-select id="prefix" wire:model="prefix" name="prefix" class="text-center @error('prefix') is-invalid @enderror w-full mt-1" :value="old('prefix', $prefix)" required autofocus autocomplete="prefix">
                <x-select-option class="text-start" hidden> -- Select Prefix -- </x-select-option>
                @foreach ($prefixes as $prefix)
                    <x-select-option wire:key="{{ $prefix->id }}" value="{{ $prefix->prefix_shortform }}" class="text-start">{{ $prefix->prefix_shortform }} - {{ $prefix->prefix }}</x-select-option>
                @endforeach
            </x-input-select>
            <x-input-error :messages="$errors->get('prefix')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="faculty_name" :value="__('Faculty Name')" />
            <x-text-input id="faculty_name" type="text" wire:model="faculty_name" name="faculty_name" placeholder="Faculty Name" class=" @error('faculty_name') is-invalid @enderror w-full mt-1" :value="old('faculty_name', $faculty_name)" required autofocus autocomplete="faculty_name" />
            <x-input-error :messages="$errors->get('faculty_name')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="text" wire:model="email" name="email" placeholder="Email" class=" @error('email') is-invalid @enderror w-full mt-1" :value="old('email', $email)" required autofocus autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="mobile_no" :value="__('Mobile Number')" />
            <x-text-input id="mobile_no" type="number" wire:model="mobile_no" name="mobile_no" placeholder="Mobile Number" class=" @error('mobile_no') is-invalid @enderror w-full mt-1" :value="old('mobile_no', $mobile_no)" required autofocus autocomplete="mobile_no" />
            <x-input-error :messages="$errors->get('mobile_no')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="department_id" :value="__('Department')" />
            <x-input-select id="department_id" wire:model="department_id" name="department_id" class="text-center @error('department_id') is-invalid @enderror w-full mt-1" :value="old('department_id', $department_id)" required autofocus autocomplete="department_id">
                <x-select-option class="text-start" hidden> -- Select Department -- </x-select-option>
                @foreach ($departments as $departmentid => $departmentname)
                    <x-select-option wire:key="{{ $departmentid }}" value="{{ $departmentid }}" class="text-start">{{ $departmentname }}</x-select-option>
                @endforeach
            </x-input-select>
            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="designation_id" :value="__('Designation')" />
            <x-input-select id="designation_id" wire:model="designation_id" name="designation_id" class="text-center @error('designation_id') is-invalid @enderror w-full mt-1" :value="old('designation_id', $designation_id)" required autofocus autocomplete="designation_id">
                <x-select-option class="text-start" hidden> -- Select Designation -- </x-select-option>
                @foreach ($designations as $designationid => $designationname)
                    <x-select-option wire:key="{{ $designationid }}" value="{{ $designationid }}" class="text-start">{{ $designationname }}</x-select-option>
                @endforeach
            </x-input-select>
            <x-input-error :messages="$errors->get('designation_id')" class="mt-2" />
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-1">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="college_id" :value="__('College')" />
            <x-input-select id="college_id" wire:model="college_id" name="college_id" class="text-center @error('college_id') is-invalid @enderror w-full mt-1" :value="old('college_id', $college_id)" required autofocus autocomplete="college_id">
                <x-select-option class="text-start" hidden> -- Select College -- </x-select-option>
                @foreach ($colleges as $collegeid => $college_name)
                    <x-select-option wire:key="{{ $collegeid }}" value="{{ $collegeid }}" class="text-start">{{ $college_name }}</x-select-option>
                @endforeach
            </x-input-select>
            <x-input-error :messages="$errors->get('college_id')" class="mt-2" />
        </div>
    </div>
</x-card-collapsible>

<x-card-collapsible heading="Bank Account Detail's">
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="account_no" :value="__('Account Number')" />
            <x-text-input id="account_no" type="number" wire:model="account_no" name="account_no" placeholder="Account Number" class=" @error('account_no') is-invalid @enderror w-full mt-1" :value="old('account_no', $account_no)" required autofocus autocomplete="account_no" />
            <x-input-error :messages="$errors->get('account_no')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="bank_address" :value="__('Bank Address')" />
            <x-text-input id="bank_address" type="text" wire:model="bank_address" name="bank_address" placeholder="Bank Address" class=" @error('bank_address') is-invalid @enderror w-full mt-1" :value="old('bank_address', $bank_address)" required autofocus autocomplete="bank_address" />
            <x-input-error :messages="$errors->get('bank_address')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="bank_name" :value="__('Bank Name')" />
            <x-input-select id="bank_name" wire:model="bank_name" name="bank_name" class="text-center @error('bank_name') is-invalid @enderror w-full mt-1" :value="old('bank_name', $bank_name)" required autofocus autocomplete="bank_name">
                <x-select-option class="text-start" hidden> -- Select Bank -- </x-select-option>
                @foreach ($banknames as $bank)
                    <x-select-option wire:key="{{ $bank->id }}" value="{{ $bank->bank_name }}" class="text-start">{{ $bank->bank_name }}</x-select-option>
                @endforeach
            </x-input-select>
            <x-input-error :messages="$errors->get('bank_name')" class="mt-2" />
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="branch_name" :value="__('Branch Name')" />
            <x-text-input id="branch_name" type="text" wire:model="branch_name" name="branch_name" placeholder="Branch Name" class=" @error('branch_name') is-invalid @enderror w-full mt-1" :value="old('branch_name', $branch_name)" required autofocus autocomplete="branch_name" />
            <x-input-error :messages="$errors->get('branch_name')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="branch_code" :value="__('Branch Code')" />
            <x-text-input id="branch_code" type="number" wire:model="branch_code" name="branch_code" placeholder="Branch Code" class=" @error('branch_code') is-invalid @enderror w-full mt-1" :value="old('branch_code', $branch_code)" required autofocus autocomplete="branch_code" />
            <x-input-error :messages="$errors->get('branch_code')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="ifsc_code" :value="__('IFSC Code')" />
            <x-text-input id="ifsc_code" type="text" wire:model="ifsc_code" name="ifsc_code" placeholder="IFSC Code" class=" @error('ifsc_code') is-invalid @enderror w-full mt-1" :value="old('ifsc_code', $ifsc_code)" required autofocus autocomplete="ifsc_code" />
            <x-input-error :messages="$errors->get('ifsc_code')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="micr_code" :value="__('MICR Code')" />
            <x-text-input id="micr_code" type="number" wire:model="micr_code" name="micr_code" placeholder="MICR Code" class=" @error('micr_code') is-invalid @enderror w-full mt-1" :value="old('micr_code', $micr_code)" required autofocus autocomplete="micr_code" />
            <x-input-error :messages="$errors->get('micr_code')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="account_type" :value="__('Account Type')" />
            <x-input-select id="account_type" wire:model="account_type" name="account_type" class="text-center @error('account_type') is-invalid @enderror w-full mt-1" :value="old('account_type', $account_type)" required autofocus autocomplete="account_type">
                <x-select-option class="text-start" hidden> -- Select Account Type -- </x-select-option>
                <x-select-option class="text-start" value="SAVING">SAVING</x-select-option>
                <x-select-option class="text-start" value="CURRENT">CURRENT</x-select-option>
            </x-input-select>
            <x-input-error :messages="$errors->get('account_type')" class="mt-2" />
        </div>
    </div>
    <x-form-btn>Submit</x-form-btn>
</x-card-collapsible>
