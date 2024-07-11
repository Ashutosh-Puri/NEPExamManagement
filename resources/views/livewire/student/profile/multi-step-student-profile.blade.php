<div>
  <div>
    <div class="mx-auto max-w-7xl sm:px-6 lg:p-2">
      {{-- Steps Header --}}
      <section>
        <x-progress-bar>
          @slot('links')
            <x-progress-link :current='$current_step' step='1' name='Student Info' />
            <x-progress-link :current='$current_step' step='2' name='Photo & Sign' />
            <x-progress-link :current='$current_step' step='3' name='Address' />
            <x-progress-link :current='$current_step' step='4' name='Choose Course' />
            <x-progress-link :current='$current_step' step='5' name='Previous Exam' />
            <x-progress-link :current='$current_step' step='6' name='Confirmation' last='1' />
          @endslot
        </x-progress-bar>
      </section>
      {{-- Student Information --}}
      @if ($current_step === 1)
        <section>
          <form wire:submit="student_information_form()">
            <div class="m-2 overflow-hidden bg-white border shadow dark:border-primary-darker dark:bg-darker rounded">
              <div class="px-2 py-2 font-semibold text-white dark:text-light bg-primary">
                Student Information <x-spinner />
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="student_name" :value="__('Student Name')" />
                  <x-input-show id="student_name" :value="$student_name" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="student_name_devnagari" :value="__('Student Name In Marathi')" />
                  <x-text-input id="student_name_devnagari" type="text" wire:model="student_name_devnagari" name="student_name_devnagari" class="w-full mt-1" :value="old('student_name_devnagari', $student_name_devnagari)"  autocomplete="student_name_devnagari" />
                  <x-input-error :messages="$errors->get('student_name_devnagari')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="aadhar_name" :value="__('Student Name On Aadhar Card')" /><x-required />
                  <x-text-input id="aadhar_name" type="text" wire:model="aadhar_name" name="aadhar_name" class="w-full mt-1" :value="old('aadhar_name', $aadhar_name)" required autocomplete="aadhar_name" />
                  <x-input-error :messages="$errors->get('aadhar_name')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="adharnumber" :value="__('Student Aadhar Number')" /><x-required />
                  <x-text-input id="adharnumber" type="number" wire:model="adharnumber" name="adharnumber" class="w-full mt-1" :value="old('adharnumber', $adharnumber)" required autocomplete="adharnumber" />
                  <x-input-error :messages="$errors->get('adharnumber')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="email" :value="__('Student Email')" />
                  <x-input-show id="email" :value="$email" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="mobile_no" :value="__('Student Mobile')" />
                  <x-input-show id="mobile_no" :value="$mobile_no" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="mother_name" :value="__('Mother Name')" />
                  <x-input-show id="mother_name" :value="$mother_name" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="mother_name_devnagari" :value="__('Mother In Marathi')" />
                  <x-text-input id="mother_name_devnagari" type="text" wire:model="mother_name_devnagari" name="mother_name_devnagari" class="w-full mt-1" :value="old('mother_name_devnagari', $mother_name_devnagari)" autocomplete="mother_name_devnagari" />
                  <x-input-error :messages="$errors->get('mother_name_devnagari')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="date_of_birth" :value="__('Date Of Birth')" /> <x-required />
                  <x-text-input id="date_of_birth" type="date" wire:model="date_of_birth" name="date_of_birth" class="w-full mt-1" :value="old('date_of_birth', $date_of_birth)" required autocomplete="date_of_birth" />
                  <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="date_of_birth_on_adharcard" :value="__('Date Of Birth As Per Aadhar Card')" /> <x-required />
                  <x-text-input id="date_of_birth_on_adharcard" type="date" wire:model="date_of_birth_on_adharcard" name="date_of_birth_on_adharcard" class="w-full mt-1" :value="old('date_of_birth_on_adharcard', $date_of_birth_on_adharcard)" required autocomplete="date_of_birth_on_adharcard" />
                  <x-input-error :messages="$errors->get('date_of_birth_on_adharcard')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="memid" :value="__('Member ID ( From I-Card Or Admission Receipt )')" /><x-required />
                  <x-text-input id="memid" type="number" wire:model="memid" name="memid" class="w-full mt-1" :value="old('memid', $memid)" required autocomplete="memid" />
                  <x-input-error :messages="$errors->get('memid')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="abcid" :value="__('ABC ID')" />
                  <x-text-input id="abcid" type="number" wire:model="abcid" name="abcid" class="w-full mt-1" :value="old('abcid', $abcid)" autocomplete="abcid" />
                  <x-input-error :messages="$errors->get('abcid')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="father_name" :value="__('Father / Husband Name')" /><x-required />
                  <x-text-input id="father_name" type="text" wire:model="father_name" name="father_name" class="w-full mt-1" :value="old('father_name', $father_name)" required autocomplete="father_name" />
                  <x-input-error :messages="$errors->get('father_name')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="father_name_devnagari" :value="__('Father / Husband Name In Marathi')" />
                  <x-text-input id="father_name_devnagari" type="text" wire:model="father_name_devnagari" name="father_name_devnagari" class="w-full mt-1" :value="old('father_name_devnagari', $father_name_devnagari)"  autocomplete="father_name_devnagari" />
                  <x-input-error :messages="$errors->get('father_name_devnagari')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="parent_name" :value="__('Guardian Name')" />
                  <x-text-input id="parent_name" type="text" wire:model="parent_name" name="parent_name" class="w-full mt-1" :value="old('parent_name', $parent_name)"  autocomplete="parent_name" />
                  <x-input-error :messages="$errors->get('parent_name')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="parent_mobile_no" :value="__('Guardian Mobile Number')" />
                  <x-text-input id="parent_mobile_no" type="number" wire:model="parent_mobile_no" name="parent_mobile_no" class="w-full mt-1" :value="old('parent_mobile_no', $parent_mobile_no)" autocomplete="parent_mobile_no" />
                  <x-input-error :messages="$errors->get('parent_mobile_no')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="caste_category_id" :value="__('Cast Category')" /><x-required />
                  <x-input-select id="caste_category_id" wire:model.live="caste_category_id" name="caste_category_id" class="text-center w-full mt-1" :value="old('caste_category_id', $caste_category_id)" required autocomplete="caste_category_id">
                    <x-select-option class="text-start" hidden> -- Select Cast Category -- </x-select-option>
                    @foreach ($cast_categories as $c_category)
                      <x-select-option wire:key="{{ $c_category->id }}" value="{{ $c_category->id }}" class="text-start">{{ $c_category->caste_category }}</x-select-option>
                    @endforeach
                  </x-input-select>
                  <x-input-error :messages="$errors->get('caste_category_id')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="caste_id" :value="__('Cast')" /><x-required />
                  <x-input-select id="caste_id" wire:model.live="caste_id" name="caste_id" class="text-center w-full mt-1" :value="old('caste_id', $caste_id)" required autocomplete="caste_id">
                    <x-select-option class="text-start" hidden> -- Select Cast -- </x-select-option>
                    @foreach ($castes as $caste)
                      <x-select-option wire:key="{{ $caste->id }}" value="{{ $caste->id }}" class="text-start">{{ $caste->caste_name }}</x-select-option>
                    @endforeach
                  </x-input-select>
                  <x-input-error :messages="$errors->get('caste_id')" class="mt-2" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="gender" :value="__('Gender')" /><x-required />
                  <x-input-select id="gender" wire:model="gender" name="gender" class="text-center w-full mt-1" :value="old('gender', $gender)" required autocomplete="gender">
                    <x-select-option class="text-start" hidden> -- Select Gender -- </x-select-option>
                    @foreach ($genders as $gender)
                      <x-select-option wire:key="{{ $gender->id }}" value="{{ $gender->gender_shortform }}" class="text-start">{{ $gender->gender }}</x-select-option>
                    @endforeach
                  </x-input-select>
                  <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="nationality" :value="__('Nationality')" /><x-required />
                  <x-input-select id="nationality" wire:model="nationality" name="nationality" class="text-center w-full mt-1" :value="old('nationality', $nationality)" required autocomplete="nationality">
                    <x-select-option class="text-start" hidden> -- Select Nationality -- </x-select-option>
                    <x-select-option class="text-start" value="Indian">Indian</x-select-option>
                    <x-select-option class="text-start" value="Other">Other</x-select-option>
                  </x-input-select>
                  <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
                </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2">
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="is_noncreamylayer" :value="__('Is Non Creamy Layer')" /><x-required />
                    <x-input-select id="is_noncreamylayer" wire:model="is_noncreamylayer" name="is_noncreamylayer" class="text-center  w-full mt-1" :value="old('is_noncreamylayer', $is_noncreamylayer)" required autocomplete="is_noncreamylayer">
                      <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
                      <x-select-option class="text-start" value="1">Yes</x-select-option>
                      <x-select-option class="text-start" value="0">No</x-select-option>
                    </x-input-select>
                    <x-input-error :messages="$errors->get('is_noncreamylayer')" class="mt-2" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="is_handicap" :value="__('Is Handicapped')" /><x-required />
                    <x-input-select id="is_handicap" wire:model="is_handicap" name="is_handicap" class="text-center  w-full mt-1" :value="old('is_handicap', $is_handicap)" required autocomplete="is_handicap">
                      <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
                      <x-select-option class="text-start" value="1">Yes</x-select-option>
                      <x-select-option class="text-start" value="0">No</x-select-option>
                    </x-input-select>
                    <x-input-error :messages="$errors->get('is_handicap')" class="mt-2" />
                  </div>
                </div>
              </div>
              <x-multi-step-btn :current_step="$current_step" :steps="$steps" />
            </div>
          </form>
        </section>
      @endif
      {{-- Upload Photo & Sign --}}
      @if ($current_step === 2)
        <section>
          <form wire:submit="photo_upload()">
            <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
              <div class="px-2 py-2 font-semibold text-white dark:text-light bg-primary">Upload Photo & Sign <x-spinner /></div>
              <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="m-5   col-span-1 rounded-md bg-white dark:bg-darker dark:border-primary-darker border">
                  <div class="flex items-center justify-between border-b p-4 dark:border-primary">
                    <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Upload Student Photo</h4>
                  </div>
                  <div class="relative h-auto p-4">
                    <div class=" text-sm text-gray-600 dark:text-gray-400 ">
                      <div class="flex flex-col items-center mx-auto space-x-6  ">
                        <div class="shrink-0 p-2 ">
                          @if ($profile_photo_path)
                            <img style="width: 135px; height: 150px;" class="object-center object-fill  " src="{{ isset($profile_photo_path) ? $profile_photo_path->temporaryUrl() : 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/no-img.png'))) . '' }}" alt="Current profile photo" />
                          @else
                            @if (file_exists($profile_photo_path_old))
                              <img style="width: 135px; height: 150px;" class="object-center object-fill rounded-md" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($profile_photo_path_old))) }}" alt="Current profile photo" />
                            @else
                              <img style="width: 135px; height: 150px;" class="object-center object-fill rounded-md" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/no-img.png'))) }}" alt="Current profile photo" />
                            @endif
                          @endif
                        </div>
                        <label class="block p-2">
                          <span class="sr-only">Choose profile photo</span>
                          <x-text-input id="profile_photo_path" wire:model.live="profile_photo_path" name="profile_photo_path" accept="image/png, image/jpeg , image/jpg" :value="old('profile_photo_path', $profile_photo_path)" autocomplete="profile_photo_path" type="file" class="block w-full text-sm dark:text-slate-500 text-black file:mr-4 file:py-2 file:px-4  border file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-darker" />
                          <x-input-error :messages="$errors->get('profile_photo_path')" class="mt-2" />
                        </label>
                        <x-input-label wire:loading.remove wire:target="profile_photo_path" class="py-2" for="profile_photo_path" :value="__('Hint : 250KB , png , jpeg , jpg')" />
                        <x-input-label wire:loading wire:target="profile_photo_path" class="py-2" for="profile_photo_path" :value="__('Uploading...')" />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="m-5 col-span-1 rounded-md bg-white dark:bg-darker dark:border-primary-darker border">
                  <div class="flex items-center justify-between border-b p-4 dark:border-primary">
                    <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Upload Student Sign</h4>
                  </div>
                  <div class="relative h-auto p-4">
                    <div class="p-5 text-sm text-gray-600 dark:text-gray-400 ">
                      <div class="flex flex-col items-center mx-auto space-x-6 ">
                        <div class="shrink-0 py-10 ">
                          @if ($sign_photo_path)
                            <img style="width: 200px; height:82px;" class="object-center object-fill bg-white" src="{{ isset($sign_photo_path) ? $sign_photo_path->temporaryUrl() : 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/no-img.png'))) . '' }}" alt="Current profile photo" />
                          @else
                            @if (file_exists($sign_photo_path_old))
                              <img style="width: 200px; height:82px;" class="object-center oobject-fill bg-white rounded-md" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($sign_photo_path_old))) }}" alt="Current profile photo" />
                            @else
                              <img style="width: 200px; height:82px;" class="object-center oobject-fill bg-white rounded-md" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/no-img.png'))) }}" alt="Current profile photo" />
                            @endif
                          @endif
                        </div>
                        <label class="block p-2">
                          <span class="sr-only">Choose profile photo</span>
                          <x-text-input id="sign_photo_path" wire:model.live="sign_photo_path" name="sign_photo_path" accept="image/png, image/jpeg , image/jpg" :value="old('sign_photo_path', $sign_photo_path)" autocomplete="sign_photo_path" type="file" class="block w-full text-sm dark:text-slate-500 text-black file:mr-4 file:py-2 file:px-4  border file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-darker" />
                          <x-input-error :messages="$errors->get('sign_photo_path')" class="mt-2" />
                        </label>
                        <x-input-label wire:loading.remove wire:target="sign_photo_path" class="py-2" for="sign_photo_path" :value="__('Hint : 50KB , png , jpeg , jpg')" />
                        <x-input-label wire:loading wire:target="sign_photo_path" class="py-2" for="sign_photo_path" :value="__('Uploading...')" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <x-multi-step-btn :current_step="$current_step" :steps="$steps" />
            </div>
          </form>
        </section>
      @endif
      {{--  Address --}}
      @if ($current_step === 3)
        <section x-data="{ open: true }">
          <form wire:submit="address_form()">
            <div class="m-2 overflow-hidden bg-white border shadow dark:border-primary-darker dark:bg-darker rounded">
              <div class="px-2 py-2 font-semibold text-white dark:text-light bg-primary">
                Student Address <x-spinner />
              </div>
              @if (isset($address_types[0]->type))
                <div class="m-5   col-span-1 rounded-md bg-white dark:bg-darker dark:border-primary-darker border">
                  <div class="flex items-center justify-between border-b p-4 dark:border-primary">
                    <h4 class="text-lg font-semibold text-gray-500 dark:text-light">
                      @if (isset($address_types[0]->type))
                        {{ $address_types[0]->type }}
                      @endif
                    </h4>
                  </div>
                  <div class="relative h-auto p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="country_id" :value="__('Country')" />
                        <x-input-select id="country_id" wire:model.live="country_id" name="country_id" class="text-center w-full mt-1" :value="old('country_id', $country_id)" required autocomplete="country_id">
                          <x-select-option class="text-start" hidden> -- Select Country -- </x-select-option>
                          @forelse ($countries as $country)
                            <x-select-option wire:key="{{ $country->id }}" value="{{ $country->id }}" class="text-start"> {{ $country->country_name }} </x-select-option>
                          @empty
                            <x-select-option class="text-start">Countries Not Found</x-select-option>
                          @endforelse
                        </x-input-select>
                        <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="state_id" :value="__('State')" />
                        <x-input-select id="state_id" wire:model.live="state_id" name="state_id" class="text-center w-full mt-1" :value="old('state_id', $state_id)" required autocomplete="state_id">
                          <x-select-option class="text-start" hidden> -- Select State -- </x-select-option>
                          @forelse ($states as $state)
                            <x-select-option wire:key="{{ $state->id }}" value="{{ $state->id }}" class="text-start"> {{ $state->state_name }} </x-select-option>
                          @empty
                            <x-select-option value="" class="text-start">Select Country First</x-select-option>
                          @endforelse
                        </x-input-select>
                        <x-input-error :messages="$errors->get('state_id')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="district_id" :value="__('District')" />
                        <x-input-select id="district_id" wire:model.live="district_id" name="district_id" class="text-center w-full mt-1" :value="old('district_id', $district_id)" required autocomplete="district_id">
                          <x-select-option class="text-start" hidden> -- Select District -- </x-select-option>
                          @forelse ($districts as $district)
                            <x-select-option wire:key="{{ $district->id }}" value="{{ $district->id }}" class="text-start"> {{ $district->district_name }} </x-select-option>
                          @empty
                            <x-select-option value="" class="text-start">Select State First</x-select-option>
                          @endforelse
                        </x-input-select>
                        <x-input-error :messages="$errors->get('district_id')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="taluka_id" :value="__('Taluka')" />
                        <x-input-select id="taluka_id" wire:model.live="taluka_id" name="taluka_id" class="text-center w-full mt-1" :value="old('taluka_id', $taluka_id)" required autocomplete="taluka_id">
                          <x-select-option class="text-start" hidden> -- Select Taluka -- </x-select-option>
                          @forelse ($talukas as $taluka)
                            <x-select-option wire:key="{{ $taluka->id }}" value="{{ $taluka->id }}" class="text-start"> {{ $taluka->taluka_name }}</x-select-option>
                          @empty
                            <x-select-option value="" class="text-start">Select District First</x-select-option>
                          @endforelse
                        </x-input-select>
                        <x-input-error :messages="$errors->get('taluka_id')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="village" :value="__('Village')" />
                        <x-text-input id="village" type="text" wire:model="village" name="village" class="w-full mt-1" :value="old('village', $village)" required autocomplete="village" />
                        <x-input-error :messages="$errors->get('village')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="pincode" :value="__('Pincode')" />
                        <x-text-input id="pincode" type="number" wire:model="pincode" name="pincode" class="w-full mt-1" :value="old('pincode', $pincode)" required autocomplete="pincode" />
                        <x-input-error :messages="$errors->get('pincode')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="address" :value="__('Address')" />
                        <x-textarea id="address" wire:model="address" rows="2" name="address" class="w-full mt-1" :value="old('address', $address)" required autocomplete="address"></x-textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <br>
                        <x-input-checkbox id="is_same" wire:model.live="is_same" @click="open = ! open" name="is_same" :value="old('is_same', $is_same)" />
                        <x-input-label for="is_same" class="inline mb-1 mx-2" :value="__('Is Permanent Address Is Same As Correspondence Address')" />
                        <x-input-error :messages="$errors->get('is_same')" class="mt-2" />
                      </div>
                    </div>
                  </div>
                </div>
              @endif
              @if (isset($address_types[1]->type))
                <div x-init="{ open: true }" x-show="open" class="m-5   col-span-1 rounded-md bg-white dark:bg-darker dark:border-primary-darker border">
                  <div class="flex items-center justify-between border-b p-4 dark:border-primary">
                    <h4 class="text-lg font-semibold text-gray-500 dark:text-light">
                      @if (isset($address_types[1]->type))
                        {{ $address_types[1]->type }}
                      @endif
                    </h4>
                  </div>
                  <div class="relative h-auto p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="country_id_2" :value="__('Country')" />
                        <x-input-select id="country_id_2" wire:model.live="country_id_2" name="country_id_2" class="text-center w-full mt-1" :value="old('country_id_2', $country_id_2)" autocomplete="country_id_2">
                          <x-select-option class="text-start" hidden> -- Select Country -- </x-select-option>
                          @foreach ($countries_2 as $country)
                            <x-select-option wire:key="{{ $country->id }}" value="{{ $country->id }}" class="text-start"> {{ $country->country_name }} </x-select-option>
                          @endforeach
                        </x-input-select>
                        <x-input-error :messages="$errors->get('country_id_2')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="state_id_2" :value="__('State')" />
                        <x-input-select id="state_id_2" wire:model.live="state_id_2" name="state_id_2" class="text-center w-full mt-1" :value="old('state_id_2', $state_id_2)" autocomplete="state_id_2">
                          <x-select-option class="text-start" hidden> -- Select State -- </x-select-option>
                          @forelse ($states_2 as $state)
                            <x-select-option wire:key="{{ $state->id }}" value="{{ $state->id }}" class="text-start"> {{ $state->state_name }} </x-select-option>
                          @empty
                            <x-select-option value="" class="text-start">Select Country First</x-select-option>
                          @endforelse
                        </x-input-select>
                        <x-input-error :messages="$errors->get('state_id_2')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="district_id_2" :value="__('District')" />
                        <x-input-select id="district_id_2" wire:model.live="district_id_2" name="district_id_2" class="text-center w-full mt-1" :value="old('district_id_2', $district_id_2)" autocomplete="district_id_2">
                          <x-select-option class="text-start" hidden> -- Select District -- </x-select-option>
                          @forelse ($districts_2 as $district)
                            <x-select-option wire:key="{{ $district->id }}" value="{{ $district->id }}" class="text-start"> {{ $district->district_name }} </x-select-option>
                          @empty
                            <x-select-option value="" class="text-start">Select State First</x-select-option>
                          @endforelse
                        </x-input-select>
                        <x-input-error :messages="$errors->get('district_id_2')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="taluka_id_2" :value="__('Taluka')" />
                        <x-input-select id="taluka_id_2" wire:model.live="taluka_id_2" name="taluka_id_2" class="text-center w-full mt-1" :value="old('taluka_id_2', $taluka_id_2)" autocomplete="taluka_id_2">
                          <x-select-option class="text-start" hidden> -- Select Taluka -- </x-select-option>
                          @forelse ($talukas_2 as $taluka)
                            <x-select-option wire:key="{{ $taluka->id }}" value="{{ $taluka->id }}" class="text-start"> {{ $taluka->taluka_name }}</x-select-option>
                          @empty
                            <x-select-option value="" class="text-start">Select District First</x-select-option>
                          @endforelse
                        </x-input-select>
                        <x-input-error :messages="$errors->get('taluka_id_2')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="village_2" :value="__('Village')" />
                        <x-text-input id="village_2" type="text" wire:model="village_2" name="village_2" class="w-full mt-1" :value="old('village_2', $village_2)" autocomplete="village_2" />
                        <x-input-error :messages="$errors->get('village_2')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="pincode_2" :value="__('Pincode')" />
                        <x-text-input id="pincode_2" type="number" wire:model="pincode_2" name="pincode_2" class="w-full mt-1" :value="old('pincode_2', $pincode_2)" autocomplete="pincode_2" />
                        <x-input-error :messages="$errors->get('pincode_2')" class="mt-2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="address_2" :value="__('Address')" />
                        <x-textarea id="address_2" wire:model="address_2" rows="2" name="address_2" class="w-full mt-1" :value="old('address_2', $address_2)" autocomplete="address_2"></x-textarea>
                        <x-input-error :messages="$errors->get('address_2')" class="mt-2" />
                      </div>
                    </div>
                  </div>
                </div>
              @endif
              {{-- @endif --}}
              <x-multi-step-btn :current_step="$current_step" :steps="$steps" />
            </div>
          </form>
        </section>
      @endif
      {{--  Choose Course --}}
      @if ($current_step === 4)
        <section>
          <form wire:submit="choose_course_form()">
            <div class="m-2 overflow-hidden bg-white border shadow dark:border-primary-darker dark:bg-darker rounded">
              <div class="px-2 py-2 font-semibold text-white dark:text-light bg-primary">
                New Course Enrollment <x-spinner />
              </div>
              <div class="grid grid-cols-1 md:grid-cols-3">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="pattern_id" :value="__('Pattern')" />
                  <x-input-select id="pattern_id" wire:model.live="pattern_id" name="pattern_id" class="text-center w-full mt-1" :value="old('pattern_id', $pattern_id)" required autocomplete="pattern_id">
                    <x-select-option class="text-start" hidden> -- Select Pattern -- </x-select-option>
                    @forelse ($patterns as $ptn)
                      <x-select-option wire:key="{{ $ptn->id }}" value="{{ $ptn->id }}" class="text-start"> {{ $ptn->pattern_name }} </x-select-option>
                    @empty
                      <x-select-option class="text-start">Patterns Not Found</x-select-option>
                    @endforelse
                  </x-input-select>
                  <x-input-error :messages="$errors->get('pattern_id')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="course_id" :value="__('Course')" />
                  <x-input-select id="course_id" wire:model.live="course_id" name="course_id" class="text-center w-full mt-1" :value="old('course_id', $course_id)" required autocomplete="course_id">
                    <x-select-option class="text-start" hidden> -- Select Course -- </x-select-option>
                    @forelse ($courses as $course)
                      <x-select-option wire:key="{{ $course->id }}" value="{{ $course->id }}" class="text-start"> {{ $course->course_name }} </x-select-option>
                    @empty
                      <x-select-option class="text-start">Courses Not Found</x-select-option>
                    @endforelse
                  </x-input-select>
                  <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="course_class_id" :value="__('Class')" _class />
                  <x-input-select id="course_class_id" wire:model.live="course_class_id" name="course_class_id" class="text-center w-full mt-1" :value="old('course_class_id', $course_class_id)" required autocomplete="course_class_id">
                    <x-select-option class="text-start" hidden> -- Select Class -- </x-select-option>
                    @forelse ($course_classes as $course_class)
                      <x-select-option wire:key="{{ $course_class->id }}" value="{{ $course_class->id }}" class="text-start"> {{ $course_class->classyear->classyear_name }} {{ $course_class->course->course_name }} </x-select-option>
                    @empty
                      <x-select-option class="text-start"> Classes Not Found</x-select-option>
                    @endforelse
                  </x-input-select>
                  <x-input-error :messages="$errors->get('course_class_id')" class="mt-2" />
                </div>
              </div>
              <x-multi-step-btn :current_step="$current_step" :steps="$steps" />
            </div>
          </form>
        </section>
      @endif
      {{--  Previous Exam Details --}}
      @if ($current_step === 5)
        <section>
          <form wire:submit="add_previous_exam_form()">
            <div class="m-2 overflow-hidden bg-white border shadow dark:border-primary-darker dark:bg-darker rounded">
              <div class="px-2 py-2 font-semibold text-white dark:text-light bg-primary">
                Previous Exam Details <x-spinner />
              </div>
              <div x-data="{ open: true }" class="grid grid-cols-1 md:grid-cols-2">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="boarduniversity_id" :value="__('Board University')" />
                  <x-input-select id="boarduniversity_id" wire:model.live="boarduniversity_id" name="boarduniversity_id" class="text-center w-full mt-1" :value="old('boarduniversity_id', $boarduniversity_id)" required autocomplete="boarduniversity_id">
                    <x-select-option class="text-start" hidden> -- Select Board University -- </x-select-option>
                    @forelse ($boarduniversities as $b_university)
                      <x-select-option wire:key="{{ $b_university->id }}" value="{{ $b_university->id }}" class="text-start"> {{ $b_university->boarduniversity_name }} </x-select-option>
                    @empty
                      <x-select-option class="text-start">Board Universities Not Found</x-select-option>
                    @endforelse
                  </x-input-select>
                  <x-input-error :messages="$errors->get('boarduniversity_id')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="educationalcourse_id" :value="__('Educational Course')" />
                  <x-input-select id="educationalcourse_id" wire:model.live="educationalcourse_id" name="educationalcourse_id" class="text-center w-full mt-1" :value="old('educationalcourse_id', $educationalcourse_id)" required autocomplete="educationalcourse_id">
                    <x-select-option class="text-start" hidden> -- Select Educational Course -- </x-select-option>
                    @forelse ($educationalcourses as $e_course)
                      <x-select-option wire:key="{{ $e_course->id }}" value="{{ $e_course->id }}" class="text-start"> {{ $e_course->course_name }} </x-select-option>
                    @empty
                      <x-select-option class="text-start">Educational Course Not Found</x-select-option>
                    @endforelse
                  </x-input-select>
                  <x-input-error :messages="$errors->get('educationalcourse_id')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="passout_year" :value="__('Previous Passout Year')" />
                  <x-input-select id="passout_year" wire:model.live="passout_year" name="passout_year" class="text-center w-full mt-1" :value="old('passout_year', $passout_year)" required autocomplete="passout_year">
                    <x-select-option class="text-start" hidden> -- Select Passout Year -- </x-select-option>
                    @forelse ($passoutyears as $p_years)
                      <x-select-option wire:key="{{ $p_years->id }}" value="{{ $p_years->year_name }}" class="text-start"> {{ $p_years->year_name }} </x-select-option>
                    @empty
                      <x-select-option class="text-start">Passout Year Not Found</x-select-option>
                    @endforelse
                  </x-input-select>
                  <x-input-error :messages="$errors->get('passout_year')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="passout_month" :value="__('Previous Passout Month')" />
                  <x-input-select id="passout_month" wire:model.live="passout_month" name="passout_month" class="text-center w-full mt-1" :value="old('passout_month', $passout_month)" required autocomplete="passout_month">
                    <x-select-option class="text-start" hidden> -- Select Passout Month -- </x-select-option>
                    @forelse ($passoutmonths as  $p_month)
                      <x-select-option wire:key="{{ $p_month->id }}" value="{{ $p_month->month_name }}" class="text-start"> {{ $p_month->month_name }} </x-select-option>
                    @empty
                      <x-select-option class="text-start">Passout Month Not Found</x-select-option>
                    @endforelse
                  </x-input-select>
                  <x-input-error :messages="$errors->get('passout_month')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="grade" :value="__('Previous Class Grade')" />
                  <x-input-select id="grade" wire:model.live="grade" name="grade" class="text-center w-full mt-1" :value="old('grade', $grade)" required autocomplete="grade">
                    <x-select-option class="text-start" hidden> -- Select Grade -- </x-select-option>
                    @forelse ($grades as $gra)
                      <x-select-option wire:key="{{ $gra->id }}" value="{{ $gra->grade_name }}" class="text-start"> {{ $gra->grade_name }} </x-select-option>
                    @empty
                      <x-select-option class="text-start">Grades Not Found</x-select-option>
                    @endforelse
                  </x-input-select>
                  <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                  <x-input-label for="seat_number" :value="__('Previous Seat Number')" />
                  <x-text-input id="seat_number" type="text" wire:model="seat_number" name="seat_number" class="w-full mt-1" :value="old('seat_number', $seat_number)" autocomplete="seat_number" />
                  <x-input-error :messages="$errors->get('seat_number')" class="mt-2" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2">
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <br>
                    <label class="inline-flex items-center text-sm space-x-2 cursor-pointer dark:text-gray-100 ">
                      <span>Percentage</span>
                      <x-toggle @click="open = ! open" id="is_cgpa" name="is_cgpa" wire:model.live="is_cgpa" />
                      <span>CGPA</span>
                    </label>
                  </div>
                  <div x-show="! open" class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="cgpa" :value="__('CGPA')" />
                    <x-text-input id="cgpa" type="text" wire:model="cgpa" name="cgpa" class="w-full mt-1" :value="old('cgpa', $cgpa)" autocomplete="cgpa" />
                    <x-input-error :messages="$errors->get('cgpa')" class="mt-2" />
                  </div>
                  <div x-show="open" class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="obtained_marks" :value="__('Previous Marks Obtained')" />
                    <x-text-input id="obtained_marks" type="number" wire:model.debounce.2000ms="obtained_marks" name="obtained_marks" class="w-full mt-1" :value="old('obtained_marks', $obtained_marks)" autocomplete="obtained_marks" />
                    <x-input-error :messages="$errors->get('obtained_marks')" class="mt-2" />
                  </div>
                </div>
                <div x-show="open" class="grid grid-cols-1 md:grid-cols-2">
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="total_marks" :value="__('Previous Marks Outof')" />
                    <x-text-input id="total_marks" type="number" wire:model.blur="total_marks" name="total_marks" class="w-full mt-1" :value="old('total_marks', $total_marks)" autocomplete="total_marks" />
                    <x-input-error :messages="$errors->get('total_marks')" class="mt-2" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="percentage" :value="__('Percentage')" />
                    <x-text-input id="percentage" type="text" wire:model="percentage" name="percentage" class="w-full mt-1" :value="old('percentage', $percentage)" autocomplete="percentage" />
                    <x-input-error :messages="$errors->get('percentage')" class="mt-2" />
                  </div>
                </div>
              </div>
              <div class="h-20 p-2 flex-row">
                @if ($current_step !== 1)
                  <button wire:loading.attr="disabled" wire:click="back()" type="button" class=" float-start  text-white bg-primary hover:bg-primary-dark focus:ring-4 focus:outline-none focus:ring-primary-darker font-medium rounded-lg text-sm p-2 text-center flex items-center me-2 dark:bg-primary dark:hover:bg-primary-dark dark:focus:ring-primary-darker">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M21 16.811c0 .864-.933 1.405-1.683.977l-7.108-4.062a1.125 1.125 0 010-1.953l7.108-4.062A1.125 1.125 0 0121 8.688v8.123zM11.25 16.811c0 .864-.933 1.405-1.683.977l-7.108-4.062a1.125 1.125 0 010-1.953L9.567 7.71a1.125 1.125 0 011.683.977v8.123z" />
                    </svg>
                    <span class="mx-1">Back</span>
                  </button>
                @endif
                @if ($current_step < $steps)
                  <button wire:loading.attr="disabled" type="button" wire:click='previous_exam_form()' class=" float-right  text-white bg-primary hover:bg-primary-dark focus:ring-4 focus:outline-none focus:ring-primary-darker font-medium rounded-lg text-sm p-2 text-center flex items-center me-2 dark:bg-primary dark:hover:bg-primary-dark dark:focus:ring-primary-darker">
                    <span class="mx-1"> Save & Next</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.688c0-.864.933-1.405 1.683-.977l7.108 4.062a1.125 1.125 0 010 1.953l-7.108 4.062A1.125 1.125 0 013 16.81V8.688zM12.75 8.688c0-.864.933-1.405 1.683-.977l7.108 4.062a1.125 1.125 0 010 1.953l-7.108 4.062a1.125 1.125 0 01-1.683-.977V8.688z" />
                    </svg>
                  </button>
                @endif
                <button wire:loading.attr="disabled" type="submit" class=" float-right  text-white bg-primary hover:bg-primary-dark focus:ring-4 focus:outline-none focus:ring-primary-darker font-medium rounded-lg text-sm p-2 text-center flex items-center me-2 dark:bg-primary dark:hover:bg-primary-dark dark:focus:ring-primary-darker">
                  <span class="mx-1">Add</span>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                </button>
                @if ($current_step === $steps)
                  <button wire:loading.attr="disabled" type="submit" class=" float-right  text-white bg-primary hover:bg-primary-dark focus:ring-4 focus:outline-none focus:ring-primary-darker font-medium rounded-lg text-sm p-2 text-center flex items-center me-2 dark:bg-primary dark:hover:bg-primary-dark dark:focus:ring-primary-darker">
                    <span class="mx-2"> Submit</span>
                  </button>
                @endif
              </div>
              {{-- List Of Previous Eductions --}}
              <section>
                <div class="overflow-x-scroll">
                  <x-table.table>
                    <x-table.thead>
                      <x-table.tr>
                        <x-table.th>No </x-table.th>
                        <x-table.th>Board / University </x-table.th>
                        <x-table.th>Course Name </x-table.th>
                        <x-table.th>Seat No</x-table.th>
                        <x-table.th>Passout Month - Year</x-table.th>
                        <x-table.th>Percentage / CGPA</x-table.th>
                        <x-table.th>Action</x-table.th>
                      </x-table.tr>
                    </x-table.thead>
                    <x-table.tbody>
                      @foreach ($pre_eductions as $key => $item)
                        <x-table.tr wire:key="{{ $item->id }}">
                          <x-table.td>{{ $key + 1 }}</x-table.td>
                          <x-table.td>{{ $item->boarduniversity->boarduniversity_name }} </x-table.td>
                          <x-table.td> {{ $item->educationalcourse->course_name }} </x-table.td>
                          <x-table.td> {{ $item->seat_number }} </x-table.td>
                          <x-table.td> {{ $item->passing_month }} - {{ date(strtotime($item->passing_year)) ?? ' ' }} </x-table.td>
                          <x-table.td> {{ $item->percentage ?? '0.00' }} % / {{ $item->cgpa ?? '0.00' }} </x-table.td>
                          <x-table.td>
                            <x-table.delete wire:loading.attr="disabled" wire:target="delete_pre_edu" wire:click="delete_pre_edu({{ $item->id }})" />
                          </x-table.td>
                        </x-table.tr>
                      @endforeach
                    </x-table.tbody>
                  </x-table.table>
                </div>
              </section>
            </div>
          </form>
        </section>
      @endif
      {{--  Confirmation --}}
      @if ($current_step === 6)
        <section>
          <form wire:submit="confirm_form()">
            <div class="m-2 overflow-hidden bg-white border shadow dark:border-primary-darker dark:bg-darker rounded">
              <div class="px-2 py-2 font-semibold text-white dark:text-light bg-primary">
                Student Information <x-spinner />
              </div>
              <div>
                <div class="grid grid-cols-1 md:grid-cols-3">
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="student_name" :value="__('Name')" />
                    <x-input-show id='student_name' :value="$student_name" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-input-show id='email' :value="$email" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="mobile_no" :value="__('Mobile')" />
                    <x-input-show id='mobile_no' :value="$mobile_no" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="date_of_birth" :value="__('Date Of Birth')" />
                    <x-input-show id='date_of_birth' :value="$date_of_birth" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="date_of_birth_on_adharcard" :value="__('Date Of Birth As Per Adhar Card')" />
                    <x-input-show id='date_of_birth_on_adharcard' :value="$date_of_birth_on_adharcard" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="memid" :value="__('Member ID')" />
                    <x-input-show id='memid' :value="$memid" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="abcid" :value="__('ABC ID')" />
                    <x-input-show id='abcid' :value="$abcid" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="adharnumber" :value="__('Aadhar Number')" />
                    <x-input-show id='adharnumber' :value="$adharnumber" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="aadhar_name" :value="__('Aadhar Name')" />
                    <x-input-show id='aadhar_name' :value="$aadhar_name" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="father_name" :value="__('Father Name')" />
                    <x-input-show id='father_name' :value="$father_name" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="mother_name" :value="__('Mother Name')" />
                    <x-input-show id='mother_name' :value="$mother_name" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="mother_name" :value="__('Mother Name')" />
                    <x-input-show id='mother_name' :value="$mother_name" />
                  </div>
                  @php
                      $caste_name =   $category_name=''; 

                      $caste = App\Models\Caste::find($caste_id);
                      $caste_category = App\Models\Castecategory::find($caste_category_id);

                      if(isset($caste->caste_name))
                      {
                        $caste_name = $caste->caste_name;
                      }
                      if(isset($caste_category->caste_category))
                      {
                        $category_name =  $caste_category->caste_category;
                      }
                  @endphp
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="caste" :value="__('Cast')" />
                    <x-input-show id='caste' :value="$caste_name" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="caste_category" :value="__('Cast Category')" />
                    <x-input-show id='caste_category' :value="$category_name" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    @php
                      $g = $gender == 'M' ? 'Male' : ($gender == 'F' ? 'Female' : 'Transgender');
                    @endphp
                    <x-input-label for="gender" :value="__('Gender')" />
                    <x-input-show id='gender' :value="$g" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="nationality" :value="__('Nationality')" />
                    <x-input-show id='nationality' :value="$nationality" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    @php
                      $n = $is_noncreamylayer == 1 ? 'Yes' : 'No';
                    @endphp
                    <x-input-label for="is_noncreamylayer" :value="__('Is Non Creamy Layer')" />
                    <x-input-show id='is_noncreamylayer' :value="$n" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    @php
                      $h = $is_handicap == 1 ? 'Yes' : 'No';
                    @endphp
                    <x-input-label for="is_handicap" :value="__('Is Handicapped')" />
                    <x-input-show id='is_handicap' :value="$h" />
                  </div>
                </div>
              </div>
            </div>
            <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
              <div class="px-2 py-2 font-semibold text-white dark:text-light bg-primary">Uploaded Photo & Sign </div>
              <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="m-2   col-span-1 rounded-md bg-white dark:bg-darker dark:border-primary-darker border">
                  <div class="flex items-center justify-between border-b p-2 dark:border-primary">
                    <h6 class="text-md font-semibold text-gray-500 dark:text-light">Uploaded Student Photo</h6>
                  </div>
                  <div class="relative h-auto p-1">
                    <div class=" text-sm text-gray-600 dark:text-gray-400 ">
                      <div class="flex flex-col items-center mx-auto space-x-6  ">
                        <div class="shrink-0 p-1">
                          @if (file_exists($profile_photo_path_old))
                            <img style="width: 135px; height: 150px;" class="object-center object-fill rounded-md" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($profile_photo_path_old))) }}" alt="Current profile photo" />
                          @else
                            <img style="width: 135px; height: 150px;" class="object-center object-fill rounded-md" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/no-img.png'))) }}" alt="Current profile photo" />
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="m-2 col-span-1 rounded-md bg-white dark:bg-darker dark:border-primary-darker border">
                  <div class="flex items-center justify-between border-b p-2 dark:border-primary">
                    <h6 class="text-md font-semibold text-gray-500 dark:text-light">Uploaded Student Sign</h6>
                  </div>
                  <div class="relative h-auto p-1">
                    <div class="p-5 text-sm text-gray-600 dark:text-gray-400 ">
                      <div class="flex flex-col items-center mx-auto space-x-6 ">
                        <div class="shrink-0 py-3 ">
                          @if (file_exists($sign_photo_path_old))
                            <img style="width: 200px; height:82px;" class="object-center oobject-fill bg-white rounded-md" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($sign_photo_path_old))) }}" alt="Current profile photo" />
                          @else
                            <img style="width: 200px; height:82px;" class="object-center oobject-fill bg-white rounded-md" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/no-img.png'))) }}" alt="Current profile photo" />
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="m-2 overflow-hidden bg-white border shadow dark:border-primary-darker dark:bg-darker rounded">
              <div class="px-2 py-2 font-semibold text-white dark:text-light bg-primary">
                Student Address
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="m-2   col-span-1 rounded-md bg-white dark:bg-darker dark:border-primary-darker border">
                  <div class="flex items-center justify-between border-b p-2 dark:border-primary">
                    <h5 class="text-lg font-semibold text-gray-500 dark:text-light">Correspondence Address</h5>
                  </div>
                  <div class="relative h-auto p-1">
                    @php
                      $tal = App\Models\Taluka::find($taluka_id);
                    @endphp
                    @if (isset($tal->id))
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="country" :value="__('Country')" />
                        <x-input-show id='country' :value="$tal->district->state->country->country_name" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="state" :value="__('State')" />
                        <x-input-show id='state' :value="$tal->district->state->state_name" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="district" :value="__('District')" />
                        <x-input-show id='district' :value="$tal->district->district_name" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="taluka" :value="__('Taluka')" />
                        <x-input-show id='taluka' :value="$tal->taluka_name" />
                      </div>
                    @endif
                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                      <x-input-label for="village" :value="__('Village')" />
                      <x-input-show id='village' :value="$village" />
                    </div>
                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                      <x-input-label for="pincode" :value="__('Pincode')" />
                      <x-input-show id='pincode' :value="$pincode" />
                    </div>
                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                      <x-input-label for="address" :value="__('Address')" />
                      <x-textarea-show id='address' :value="$address" />
                    </div>
                  </div>
                </div>
                @php
                  $tal2 = App\Models\Taluka::find($taluka_id_2);
                @endphp
                @if (isset($tal2->id))
                  <div class="m-2   col-span-1 rounded-md bg-white dark:bg-darker dark:border-primary-darker border">
                    <div class="flex items-center justify-between border-b p-2 dark:border-primary">
                      <h5 class="text-lg font-semibold text-gray-500 dark:text-light">Permanent Address</h5>
                    </div>
                    <div class="relative h-auto p-1">
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="country_2" :value="__('Country')" />
                        <x-input-show id='country_2' :value="$tal2->district->state->country->country_name" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="state_2" :value="__('State')" />
                        <x-input-show id='state_2' :value="$tal2->district->state->state_name" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="district_2" :value="__('District')" />
                        <x-input-show id='district_2' :value="$tal2->district->district_name" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="taluka_2" :value="__('Taluka')" />
                        <x-input-show id='taluka_2' :value="$tal2->taluka_name" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="village_2" :value="__('Village')" />
                        <x-input-show id='village_2' :value="$village_2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="pincode_2" :value="__('Pincode')" />
                        <x-input-show id='pincode_2' :value="$pincode_2" />
                      </div>
                      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="address_2" :value="__('Address')" />
                        <x-textarea-show id='address_2' :value="$address_2" />
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            </div>
            <div class="m-2 overflow-hidden bg-white border shadow dark:border-primary-darker dark:bg-darker rounded">
              <div class="px-2 py-2 font-semibold text-white dark:text-light bg-primary">
                New Course Enrollment
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2">
                @php
                  $ptn_class = App\Models\Patternclass::find($pattern_class_id);
                @endphp
                @if (isset($ptn_class->id))
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="pattern" :value="__('Pattern')" />
                    <x-input-show id='pattern' :value="$ptn_class->pattern->pattern_name" />
                  </div>
                  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="class" :value="__('Class')" />
                    <x-input-show id='class' :value="$ptn_class->courseclass->classyear->classyear_name . ' ' . $ptn_class->courseclass->course->course_name" />
                  </div>
                @endif
              </div>
            </div>
            <div class="m-2 overflow-hidden bg-white border shadow dark:border-primary-darker dark:bg-darker rounded">
              <div class="px-2 py-2 font-semibold text-white dark:text-light bg-primary">
                Previous Exam Details
              </div>
              <div class="p-2 overflow-x-scroll">
                <x-table.table>
                  <x-table.thead>
                    <x-table.tr>
                      <x-table.th>No </x-table.th>
                      <x-table.th>Board / University </x-table.th>
                      <x-table.th>Course Name </x-table.th>
                      <x-table.th>Seat No</x-table.th>
                      <x-table.th>Passout Month - Year</x-table.th>
                      <x-table.th>Percentage / CGPA</x-table.th>
                    </x-table.tr>
                  </x-table.thead>
                  <x-table.tbody>
                    @foreach ($pre_eductions as $key => $item)
                      <x-table.tr wire:key="{{ $item->id }}">
                        <x-table.td>{{ $key + 1 }}</x-table.td>
                        <x-table.td>{{ $item->boarduniversity->boarduniversity_name }} </x-table.td>
                        <x-table.td> {{ $item->educationalcourse->course_name }} </x-table.td>
                        <x-table.td> {{ $item->seat_number }} </x-table.td>
                        <x-table.td> {{ $item->passing_month }} - {{ date(strtotime($item->passing_year)) ?? ' ' }} </x-table.td>
                        <x-table.td> {{ $item->percentage ?? '0.00' }} % / {{ $item->cgpa ?? '0.00' }} </x-table.td>
                      </x-table.tr>
                    @endforeach
                  </x-table.tbody>
                </x-table.table>
              </div>
              <div class="p-2">
                <div class="px-5 text-sm text-gray-600 dark:text-gray-400">
                  <br>
                  <x-text-input id="is_confirm" type="checkbox" wire:model.live="is_confirm" @click="open = ! open" class="my-1 h-8 w-8" name="is_confirm" :value="old('is_confirm', $is_confirm)" />
                  <x-input-label for="is_confirm" class="inline mb-1 mx-2 !text-xs " :value="__('Confirmation : Are You Sure You Want To Submit This Form')" />
                  <x-input-error :messages="$errors->get('is_confirm')" class="mt-2" />
                </div>
              </div>
              <x-multi-step-btn :current_step="$current_step" :steps="$steps" />
            </div>
          </form>
        </section>
      @endif
    </div>
  </div>
</div>
