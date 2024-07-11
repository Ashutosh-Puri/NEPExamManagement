<div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Instruction Type
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="instruction_type" :value="__('Instruction Type')" />
            <x-required />
            <x-text-input id="instruction_type" type="text" wire:model="instruction_type" name="instruction_type" class="w-full mt-1" :value="old('instruction_type',$instruction_type)" required autofocus autocomplete="instruction_type" />
            <x-input-error :messages="$errors->get('instruction_type')" class="mt-2" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="is_active" :value="__('Status')" />
            <x-required />
            <x-input-select id="is_active" wire:model="is_active" name="is_active" class="text-center  w-full mt-1" :value="old('is_active',$is_active)" required autocomplete="is_active">
                <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
                <x-select-option class="text-start" value="1">Active</x-select-option>
                <x-select-option class="text-start" value="0">Inactive</x-select-option>
            </x-input-select>
            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
        </div>
    </div>
    <x-form-btn>
        Submit
    </x-form-btn>
</div>
