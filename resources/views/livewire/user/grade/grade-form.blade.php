<div>
    <section>
        <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
            <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
                Grade
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="max_percentage" :value="__('Max Percentage')" />
                    <x-required />
                    <x-text-input id="max_percentage" type="number" step="any" wire:model="max_percentage" name="max_percentage" class="w-full mt-1" :value="old('max_percentage',$max_percentage)" required autofocus autocomplete="max_percentage" />
                    <x-input-error :messages="$errors->get('max_percentage')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="min_percentage" :value="__('Min Percentage')" />
                    <x-required />
                    <x-text-input id="min_percentage" type="number" step="any" wire:model="min_percentage" name="min_percentage" class="w-full mt-1" :value="old('min_percentage',$min_percentage)" required autofocus autocomplete="min_percentage" />
                    <x-input-error :messages="$errors->get('min_percentage')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="grade_point" :value="__('Grade Point ')" />
                    <x-required />
                    <x-text-input id="grade_point" type="number" step="any" wire:model="grade_point" name="grade_point" class="w-full mt-1" :value="old('grade_point',$grade_point)" required autofocus autocomplete="grade_point" />
                    <x-input-error :messages="$errors->get('grade_point')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="grade_name" :value="__('Grade Name ')" />
                    <x-required />
                    <x-text-input id="grade_name" type="text" wire:model="grade_name" name="grade_name" class="w-full mt-1" :value="old('grade_name',$grade_name)" required autofocus autocomplete="grade_name" />
                    <x-input-error :messages="$errors->get('grade_name')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="description" :value="__('Description ')" />
                    <x-required />
                    <x-text-input id="description" type="text" wire:model="description" name="description" class="w-full mt-1" :value="old('description',$description)" autofocus autocomplete="description" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="is_active" :value="__('Status')" />
                    <x-required />
                    <x-input-select id="is_active" wire:model="is_active" name="is_active" class="text-center  w-full mt-1" :value="old('is_active',$is_active)" required autocomplete="is_active">
                        <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
                        <x-select-option class="text-start" value="0">Inactive</x-select-option>
                        <x-select-option class="text-start" value="1">Active</x-select-option>
                    </x-input-select>
                    <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                </div>
            </div>

            <div class="h-20 p-2">
                <x-form-btn>
                    Submit
                </x-form-btn>
            </div>
        </div>
</div>
