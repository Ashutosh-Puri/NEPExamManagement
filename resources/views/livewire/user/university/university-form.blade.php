<div>
    <section>
        <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
            <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
                University
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="university_name" :value="__('University Name')" />
                    <x-required />
                    <x-text-input id="university_name" type="text" wire:model="university_name" name="university_name" class="w-full mt-1" :value="old('university_name',$university_name)" required autofocus autocomplete="university_name" />
                    <x-input-error :messages="$errors->get('university_name')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="university_email" :value="__('University Email')" />
                    <x-required />
                    <x-text-input id="university_email" type="email" wire:model="university_email" name="university_email" class="w-full mt-1" :value="old('university_email',$university_email)" required autofocus autocomplete="university_email" />
                    <x-input-error :messages="$errors->get('university_email')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2">
                <div>

                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="university_contact_no" :value="__('University Contact No')" />
                        <x-required />
                        <x-text-input id="university_contact_no" type="number" wire:model="university_contact_no" name="university_contact_no" class="w-full mt-1" :value="old('university_contact_no',$university_contact_no)" required autofocus autocomplete="university_contact_no" />
                        <x-input-error :messages="$errors->get('university_contact_no')" class="mt-2" />
                    </div>

                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="university_address" :value="__('University Address')" />
                        <x-required />
                        <x-textarea id="university_address" type="text" wire:model="university_address" name="university_address" class="w-full mt-1" :value="old('university_address',$university_address)" required autofocus autocomplete="university_address" />
                        <x-input-error :messages="$errors->get('university_address')" class="mt-2" />
                    </div>

                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="university_website_url" :value="__('University Website URL ')" />
                        <x-required />
                        <x-text-input id="university_website_url" type="text" wire:model="university_website_url" name="university_website_url" class="w-full mt-1" :value="old('university_website_url',$university_website_url)" required autofocus autocomplete="university_website_url" />
                        <x-input-error :messages="$errors->get('university_website_url')" class="mt-2" />
                    </div>

                    {{-- status --}}

                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="status" :value="__('Status')" />
                        <x-required />
                        <x-input-select id="status" wire:model="status" name="status" class="text-center  w-full mt-1" :value="old('status',$status)" required autocomplete="status">
                            <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
                            <x-select-option class="text-start" value="0">Inactive</x-select-option>
                            <x-select-option class="text-start" value="1">Active</x-select-option>
                        </x-input-select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>

                {{-- upload logo --}}
                <section>

                    <div class="m-5  col-span-1 rounded-md bg-white dark:bg-darker dark:border-primary-darker border">
                        <div class="flex items-center justify-between border-b p-4 dark:border-primary">
                            <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Upload University Logo</h4>
                        </div>
                        <div class="relative h-auto p-4">
                            <div class=" text-sm text-gray-600 dark:text-gray-400 ">
                                <div class="flex flex-col items-center mx-auto space-x-6  ">
                                    <div class="shrink-0 p-2">
                                        @if ($university_logo_path)
                                        <img style="width: 135px; height: 150px;" class="object-center object-fill" src="{{ isset($university_logo_path)?$university_logo_path->temporaryUrl():asset('img/no-img.png'); }}" alt="Current profile photo" />
                                        @else
                                        <img style="width: 135px; height: 150px;" class="object-center object-fill " src="{{ isset($university_logo_path_old)?asset($university_logo_path_old):asset('img/no-img.png'); }}" alt="Current profile photo" />
                                        @endif
                                    </div>
                                    <label class="block p-2">
                                        <span class="sr-only">Choose profile photo</span>
                                        <x-text-input id="university_logo_path" wire:model.live="university_logo_path" name="university_logo_path" accept="image/png, image/jpeg , image/jpg" :value="old('university_logo_path',$university_logo_path)" autofocus autocomplete="university_logo_path" type="file" class="block w-full text-sm dark:text-slate-500 text-black file:mr-4 file:py-2 file:px-4  border file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-darker" />
                                        <x-input-error :messages="$errors->get('university_logo_path')" class="mt-2" />
                                    </label>
                                    <x-input-label class="py-2" wire:loading.remove wire:target="university_logo_path" for="university_logo_path" :value="__('Hint : 250KB , png , jpeg , jpg')" />
                                    <x-input-label class="py-2" wire:loading wire:target="university_logo_path" for="university_logo_path" :value="__('Uploading...')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="h-20 p-2">
                <x-form-btn>
                    Submit
                </x-form-btn>
            </div>
        </div>
</div>

