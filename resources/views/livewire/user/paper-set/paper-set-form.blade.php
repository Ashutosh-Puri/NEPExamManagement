<div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Paper Set
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="set_name" :value="__('Set Name')" />
            <x-required />
            <x-text-input id="set_name" type="text" wire:model="set_name" name="set_name" class="w-full mt-1" :value="old('set_name',$set_name)" required autofocus autocomplete="set_name" />
            <x-input-error :messages="$errors->get('set_name')" class="mt-2" />
        </div>
    </div>
    <x-form-btn wire:target="college_logo_path" wire:loading.attr="disable">
        Submit
    </x-form-btn>
</div>
