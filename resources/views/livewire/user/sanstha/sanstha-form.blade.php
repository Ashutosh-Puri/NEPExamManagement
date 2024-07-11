<div>
    <section>
        <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
            <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
                Sanstha
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="sanstha_name" :value="__('Sanstha Name')" />
                    <x-required />
                    <x-text-input id="sanstha_name" type="text" wire:model="sanstha_name" name="sanstha_name" class="w-full mt-1" :value="old('sanstha_name',$sanstha_name)" required autofocus autocomplete="sanstha_name" />
                    <x-input-error :messages="$errors->get('sanstha_name')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="sanstha_chairman_name" :value="__('Sanstha Chairman Name')" />
                    <x-required />
                    <x-text-input id="sanstha_chairman_name" type="text" wire:model="sanstha_chairman_name" name="sanstha_chairman_name" class="w-full mt-1" :value="old('sanstha_chairman_name',$sanstha_chairman_name)" required autofocus autocomplete="sanstha_chairman_name" />
                    <x-input-error :messages="$errors->get('sanstha_chairman_name')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2">

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="sanstha_contact_no" :value="__('Sanstha Contact No')" />
                    <x-required />
                    <x-text-input id="sanstha_contact_no" type="number" wire:model="sanstha_contact_no" name="sanstha_contact_no" class="w-full mt-1" :value="old('sanstha_contact_no',$sanstha_contact_no)" required autofocus autocomplete="sanstha_contact_no" />
                    <x-input-error :messages="$errors->get('sanstha_contact_no')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="sanstha_website_url" :value="__('Sanstha Website URL ')" />
                    <x-required />
                    <x-text-input id="sanstha_website_url" type="text" wire:model="sanstha_website_url" name="sanstha_website_url" class="w-full mt-1" :value="old('sanstha_website_url',$sanstha_website_url)" required autofocus autocomplete="sanstha_website_url" />
                    <x-input-error :messages="$errors->get('sanstha_website_url')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="sanstha_address" :value="__('Sanstha Address')" />
                    <x-required />
                    <x-textarea id="sanstha_address" type="text" wire:model="sanstha_address" name="sanstha_address" class="w-full mt-1" :value="old('sanstha_address',$sanstha_address)" required autofocus autocomplete="sanstha_address" />
                    <x-input-error :messages="$errors->get('sanstha_address')" class="mt-2" />
                </div>

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
            <div class="h-20 p-2">
                <x-form-btn>
                    Submit
                </x-form-btn>
            </div>
        </div>
</div>
</div>
