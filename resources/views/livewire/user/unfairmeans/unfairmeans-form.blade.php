<div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Unfairmean's
        <x-spinner />
    </div>
    <div class="grid grid-cols-1 md:grid-cols-1">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="location" :value="__('Place of Meeting')" />
            <x-required />
            <x-text-input id="location" type="text" wire:model="location" name="location" class="w-full mt-1" :value="old('location',$location)" required autofocus autocomplete="location" />
            <x-input-error :messages="$errors->get('location')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="date" :value="__('Date')" />
            <x-required />
            <x-text-input id="date" type="date" wire:model="date" placeholder="{{ __('Date') }}" name="date" class="w-full mt-1" :value="old('date', $date)" autocomplete="date" />
            <x-input-error :messages="$errors->get('date')" class="mt-1" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="time" :value="__('Time')" />
            <x-required />
            <x-text-input id="time" type="time" wire:model="time" name="time" class="w-full mt-1" :value="old('time',$time)" required autofocus autocomplete="time" />
            <x-input-error :messages="$errors->get('time')" class="mt-2" />
        </div>
    </div>
    <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
