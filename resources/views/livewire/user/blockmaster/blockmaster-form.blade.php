<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Block Master
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3">
      
      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="block_name" :value="__('Block Name')" />
        <x-required />
        <x-text-input id="block_name" type="text" wire:model="block_name"  name="block_name" class="w-full mt-1" :value="old('block_name', $block_name)" autocomplete="block_name" />
        <x-input-error :messages="$errors->get('block_name')" class="mt-1" />
      </div>

      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="block_size" :value="__('Block Size')" />
        <x-required />
        <x-text-input id="block_size" type="number" wire:model="block_size"  name="block_size" class="w-full mt-1" :value="old('block_size', $block_size)" autocomplete="block_size" />
        <x-input-error :messages="$errors->get('block_size')" class="mt-1" />
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
    <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
  </div>
  