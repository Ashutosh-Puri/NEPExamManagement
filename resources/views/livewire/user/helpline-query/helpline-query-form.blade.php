<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Helpline Query
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="query_name" :value="__('Query Name')" />
      <x-text-input id="query_name" type="text" wire:model="query_name" placeholder="{{ __('Enter Query Name') }}" name="query_name" class="w-full mt-1" :value="old('query_name', $query_name)" autocomplete="query_name" />
      <x-input-error :messages="$errors->get('query_name')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="is_active" :value="__('Status')" /> <br>
      <x-input-checkbox id="is_active"  wire:model="is_active"  name="is_active" :value="old('is_active',$is_active)" />
      <x-input-label for="is_active" class="inline mb-1 mx-2" :value="__('Make In Active')" />
      <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
    </div>
  </div>
  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
