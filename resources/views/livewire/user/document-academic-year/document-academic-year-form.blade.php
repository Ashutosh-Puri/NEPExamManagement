<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Document Academic Year
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="year_name" :value="__('Academic Year Name')" />
            <x-text-input id="year_name" type="text" wire:model="year_name" placeholder="{{ __('Enter Academic Year Name') }}" name="year_name" class="w-full mt-1" :value="old('year_name', $year_name)" autocomplete="year_name" />
            <x-input-error :messages="$errors->get('year_name')" class="mt-1" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="description" :value="__('Description')" />
            <x-text-input id="description" type="text" wire:model="description" placeholder="{{ __('Enter Description') }}"  name="description" class="w-full mt-1" :value="old('description', $description)" autocomplete="description" />
            <x-input-error :messages="$errors->get('description')" class="mt-1" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="start_date" :value="__('Start Date')" />
            <x-text-input id="start_date" type="date" wire:model="start_date" placeholder="{{ __('Start Date') }}" name="start_date" class="w-full mt-1" :value="old('start_date', $start_date)" autocomplete="start_date" />
            <x-input-error :messages="$errors->get('start_date')" class="mt-1" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="end_date" :value="__('End Date')" />
            <x-text-input id="end_date" type="date" wire:model="end_date" placeholder="{{ __('End Date') }}" name="end_date" class="w-full mt-1" :value="old('end_date', $end_date)" autocomplete="end_date" />
            <x-input-error :messages="$errors->get('end_date')" class="mt-1" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="active" :value="__('active')" /> <br>
            <x-input-checkbox id="active" wire:model="active" name="active" :value="old('active',$active)" />
            <x-input-label for="active" class="inline mb-1 mx-2" :value="__('Make In Active')" />
            <x-input-error :messages="$errors->get('active')" class="mt-2" />
        </div>

    </div>
    <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
