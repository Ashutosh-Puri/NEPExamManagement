<div>
    <section>
        <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
            <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
                Department Type
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="departmenttype" :value="__('Department Type')" />
                    <x-required />
                    <x-text-input id="departmenttype" type="text" wire:model="departmenttype" name="departmenttype" class="w-full mt-1" :value="old('departmenttype',$departmenttype)" required autofocus autocomplete="departmenttype" />
                    <x-input-error :messages="$errors->get('departmenttype')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="description" :value="__('Description')" />
                    <x-text-input id="description" type="text" wire:model="description" name="description" class="w-full mt-1" :value="old('description',$description)"  autofocus autocomplete="description" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
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
    </section>
</div>
