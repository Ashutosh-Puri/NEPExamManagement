<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
   Board / University
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="boarduniversity_name" :value="__(' Board / University')" />
      <x-text-input id="boarduniversity_name" type="text" wire:model="boarduniversity_name" placeholder="{{ __('Enter  Board / University') }}" name="boarduniversity_name" class="w-full mt-1" :value="old('boarduniversity_name', $boarduniversity_name)" autocomplete="boarduniversity_name" />
      <x-input-error :messages="$errors->get('boarduniversity_name')" class="mt-1" />
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
