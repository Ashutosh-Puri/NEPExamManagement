<x-card-collapsible heading="Personal Details">
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="prefix" :value="__('Prefix')" />
                    <x-input-show id="prefix" :value="$prefix" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="faculty_name" :value="__('Faculty Name')" />
                    <x-input-show id="faculty_name" :value="$faculty_name" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-input-show id="email" :value="$email" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="mobile_no" :value="__('Mobile Number')" />
                    <x-input-show id="mobile_no" :value="$mobile_no" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="date_of_birth" :value="__('Date of birth')" />
                    <x-text-input id="date_of_birth" type="date" wire:model="date_of_birth" name="date_of_birth" class="@error('date_of_birth') is-invalid @enderror w-full mt-1" :value="old('date_of_birth', $date_of_birth)" required autofocus autocomplete="date_of_birth" />
                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="gender" :value="__('Gender')" />
                    <x-input-select id="gender" wire:model="gender" name="gender" class="text-center @error('gender') is-invalid @enderror w-full mt-1" :value="old('gender', $gender)" required autofocus autocomplete="gender">
                        <x-select-option class="text-start" hidden> -- Select Gender -- </x-select-option>
                        @forelse ($genders as $genderid => $gender)
                            <x-select-option wire:key="{{ $genderid }}" value="{{ $genderid }}" class="text-start">{{ $gender }}</x-select-option>
                        @empty
                            <x-select-option class="text-start">Genders Not Found</x-select-option>
                        @endforelse
                    </x-input-select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="pan" :value="__('PAN')" />
                    <x-text-input id="pan" type="text" wire:model="pan" class="@error('pan') is-invalid @enderror w-full mt-1" :value="old('pan', $pan)" required autofocus autocomplete="pan" />
                    <x-input-error :messages="$errors->get('pan')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="category" :value="__('Category')" />
                    <x-input-select id="category" wire:model="category" name="category" class="text-center @error('category') is-invalid @enderror w-full mt-1" :value="old('category', $category)" required autofocus autocomplete="category">
                        <x-select-option class="text-start" hidden> -- Select Category -- </x-select-option>
                        @forelse ($cast_categories as $cast_categoryid => $cast_category)
                            <x-select-option wire:key="{{ $cast_categoryid }}" value="{{ $cast_categoryid }}" class="text-start">{{ $cast_category }}</x-select-option>
                        @empty
                            <x-select-option class="text-start">Category Not Found</x-select-option>
                        @endforelse
                    </x-input-select>
                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                </div>
            </div>
        </div>

        <div>
            <div class="m-5 col-span-1 rounded-md bg-white dark:bg-darker dark:border-primary-darker border">
                <div class="flex items-center justify-between border-b p-2 dark:border-primary">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-light">Upload Profile Photo</h4>
                </div>
                <div class="relative h-auto p-2">
                    <div class=" text-sm text-gray-600 dark:text-gray-400 ">
                        <div class="flex flex-col items-center mx-auto space-x-6  ">
                            <div class="shrink-0 p-2">
                                @if ($profile_photo_path)
                                    <img style="width: 135px; height: 150px;" class="object-center object-fill  " src="{{ isset($profile_photo_path) ? $profile_photo_path->temporaryUrl() : asset('img/no-img.png') }}" alt="Current profile photo" />
                                @else
                                    <img style="width: 135px; height: 150px;" class="object-center object-fill "src="{{ isset($profile_photo_path_old) ? asset($profile_photo_path_old) : asset('img/no-img.png') }}" alt="Current profile photo" />
                                @endif
                            </div>
                            <label class="block p-2">
                                <span class="sr-only">Choose profile photo</span>
                                <x-text-input id="profile_photo_path" wire:model="profile_photo_path" name="profile_photo_path" accept="image/png, image/jpeg , image/jpg" :value="old('profile_photo_path', $profile_photo_path)" autocomplete="profile_photo_path" type="file" class="block w-full text-sm dark:text-slate-500 text-black file:mr-4 file:py-2 file:px-4  border file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-darker" />
                                <x-input-error :messages="$errors->get('profile_photo_path')" class="mt-2" />
                            </label>
                            <x-input-label wire:loading.remove wire:target="profile_photo_path" class="py-2" for="profile_photo_path" :value="__('Hint : 250KB , png , jpeg , jpg')" />
                            <x-input-label wire:loading wire:target="profile_photo_path" class="py-2" for="profile_photo_path" :value="__('Uploading...')" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="current_address" :value="__('Current Address')" />
            <x-textarea id="current_address" type="text" wire:model="current_address" class="@error('current_address') is-invalid @enderror w-full mt-1" :value="old('current_address', $current_address)" required autofocus autocomplete="current_address"></x-textarea>
            <x-input-error :messages="$errors->get('current_address')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="permanant_address" :value="__('Permanant Address')" />
            <x-textarea id="permanant_address" wire:model="permanant_address" type="text" class="@error('permanant_address') is-invalid @enderror w-full mt-1" :value="old('permanant_address', $permanant_address)" required autofocus autocomplete="permanant_address"></x-textarea>
            <x-input-error :messages="$errors->get('permanant_address')" class="mt-2" />
        </div>
    </div>
</x-card-collapsible>


<x-card-collapsible heading="Working Details">
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="department_id" :value="__('Department')" />
            <x-input-show id="department_id" :value="$department_id" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="designation_id" :value="__('Designation')" />
            <x-input-show id="designation_id" :value="$designation_id" />
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-1">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="college_id" :value="__('College')" />
            <x-input-show id="college_id" :value="$college_id" />
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="unipune_id" :value="__('Unipune ID')" />
            <x-text-input id="unipune_id" type="text" wire:model="unipune_id" class="@error('unipune_id') is-invalid @enderror w-full mt-1" :value="old('unipune_id', $unipune_id)" required autofocus autocomplete="unipune_id" />
            <x-input-error :messages="$errors->get('unipune_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="qualification" :value="__('Qualification')" />
            <x-text-input id="qualification" type="text" wire:model="qualification" class="@error('qualification') is-invalid @enderror w-full mt-1" :value="old('qualification', $qualification)" required autofocus autocomplete="qualification" />
            <x-input-error :messages="$errors->get('qualification')" class="mt-2" />
        </div>
    </div>
</x-card-collapsible>


<x-card-collapsible heading="Bank Account Details">

    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="account_no" :value="__('Account Number')" />
            <x-input-show id="account_no" :value="$account_no" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="bank_address" :value="__('Bank Address')" />
            <x-input-show id="bank_address" :value="$bank_address" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="bank_name" :value="__('Bank Name')" />
            <x-input-show id="bank_name" :value="$bank_name" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="branch_name" :value="__('Branch Name')" />
            <x-input-show id="branch_name" :value="$branch_name" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="branch_code" :value="__('Branch Code')" />
            <x-input-show id="branch_code" :value="$branch_code" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="ifsc_code" :value="__('IFSC Code')" />
            <x-input-show id="ifsc_code" :value="$ifsc_code" />
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="micr_code" :value="__('MICR Code')" />
            <x-input-show id="micr_code" :value="$micr_code" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="account_type" :value="__('Account Type')" />
            <x-input-show id="account_type" :value="$account_type" />
        </div>
    </div>
</x-card-collapsible>
<x-form-btn>Submit</x-form-btn>
