<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Exam Session
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="from_date" :value="__('From Date')" />
            <x-text-input id="from_date" type="date" wire:model="from_date" placeholder="{{ __('Result Date') }}" name="from_date" class="w-full mt-1" :value="old('from_date', $from_date)" autocomplete="from_date" />
            <x-input-error :messages="$errors->get('from_date')" class="mt-1" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="to_date" :value="__('To Date')" />
            <x-text-input id="to_date" type="date" wire:model="to_date" placeholder="{{ __('Result Date') }}" name="to_date" class="w-full mt-1" :value="old('to_date', $to_date)" autocomplete="to_date" />
            <x-input-error :messages="$errors->get('to_date')" class="mt-1" />
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-3">

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="from_time" :value="__('From Time')" />
            <x-required />
            <x-text-input id="from_time" type="time" wire:model="from_time" name="from_time" class="w-full mt-1" :value="old('from_time',$from_time)" required autofocus autocomplete="from_time" />
            <x-input-error :messages="$errors->get('from_time')" class="mt-2" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="to_time" :value="__('To Time')" />
            <x-required />
            <x-text-input id="to_time" type="time" wire:model="to_time" name="to_time" class="w-full mt-1" :value="old('to_time',$to_time)" required autofocus autocomplete="to_time" />
            <x-input-error :messages="$errors->get('to_time')" class="mt-2" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="session_type" :value="__('Session Type')" />
            <x-required />
            <x-input-select id="session_type" wire:model="session_type" name="session_type" class="text-center  w-full mt-1" :value="old('session_type',$session_type)" required autocomplete="session_type">
                <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
                <x-select-option class="text-start" value="1">M</x-select-option>
                <x-select-option class="text-start" value="2">E</x-select-option>
            </x-input-select>
            <x-input-error :messages="$errors->get('session_type')" class="mt-2" />
        </div>

    </div>
    <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
    </div>
