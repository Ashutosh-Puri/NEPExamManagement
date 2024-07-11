<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <section>
        <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
            Building
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="building_name" :value="__('Building Name')" />
                <x-required />
                <x-text-input id="building_name" type="text" wire:model="building_name" name="building_name" class="w-full mt-1" :value="old('building_name',$building_name)" required autofocus autocomplete="building_name" />
                <x-input-error :messages="$errors->get('building_name')" class="mt-2" />
            </div>

            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="Priority" :value="__(' Priority')" />
                <x-required />
                <x-text-input id="Priority" type="text" wire:model="Priority" name="Priority" class="w-full mt-1" :value="old('Priority',$Priority)" required autofocus autocomplete="Priority" />
                <x-input-error :messages="$errors->get('Priority')" class="mt-2" />
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
