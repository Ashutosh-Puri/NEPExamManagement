<div>
    <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
        <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
            Rate Head
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="headname" :value="__('Head Name')" />
                <x-required />
                <x-text-input id="headname" type="text" wire:model="headname" name="headname" class="w-full mt-1" :value="old('headname',$headname)" required autofocus autocomplete="headname" />
                <x-input-error :messages="$errors->get('headname')" class="mt-2" />
            </div>

            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="type" :value="__('Type')" />
                <x-required />
                <x-text-input id="type" type="text" wire:model="type" name="type" class="w-full mt-1" :value="old('type',$type)" required autofocus autocomplete="type" />
                <x-input-error :messages="$errors->get('type')" class="mt-2" />
            </div>

            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="noofcredit" :value="__('No of Credit')" />
                <x-required />
                <x-text-input id="noofcredit" type="number" step="any" wire:model="noofcredit" name="noofcredit" class="w-full mt-1" :value="old('noofcredit',$noofcredit)" required autofocus autocomplete="noofcredit" />
                <x-input-error :messages="$errors->get('noofcredit')" class="mt-2" />
            </div>

            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="course_type" :value="__('Course Type')" />
                <x-required />
                <x-text-input id="course_type" type="text"  wire:model="course_type" name="course_type" class="w-full mt-1" :value="old('course_type',$course_type)" required autofocus autocomplete="course_type" />
                <x-input-error :messages="$errors->get('course_type')" class="mt-2" />
            </div>

             <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="amount" :value="__('Amount')" />
                <x-required />
                <x-text-input id="amount" type="number" step="any" wire:model="amount" name="amount" class="w-full mt-1" :value="old('amount',$amount)" required autofocus autocomplete="amount" />
                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
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
            <x-form-btn wire:target="college_logo_path" wire:loading.attr="disable">
                Submit
            </x-form-btn>
        </div>
    </div>
</div>
