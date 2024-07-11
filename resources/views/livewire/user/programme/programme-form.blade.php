<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Programme
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="programme_name" :value="__('Programme Name')" />
      <x-text-input id="programme_name" type="text" wire:model="programme_name" placeholder="{{ __('Enter Programme Name') }}" name="programme_name" class="w-full mt-1" :value="old('programme_name', $programme_name)" autocomplete="programme_name" />
      <x-input-error :messages="$errors->get('programme_name')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="active" :value="__('Status')" /> <br>
      <x-input-checkbox id="active"  wire:model="active"  name="active" :value="old('active',$active)" />
      <x-input-label for="active" class="inline mb-1 mx-2" :value="__('Make In Active')" />
      <x-input-error :messages="$errors->get('active')" class="mt-2" />
    </div>
  </div>
  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
