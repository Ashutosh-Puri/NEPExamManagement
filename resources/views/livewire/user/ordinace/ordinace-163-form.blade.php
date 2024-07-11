<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
   Ordinace 163
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="activity_name" :value="__('Activity Name')" />
      <x-text-input id="activity_name" type="text" wire:model="activity_name" placeholder="{{ __('Enter Activity Name') }}" name="activity_name" class="w-full mt-1" :value="old('activity_name', $activity_name)" autocomplete="activity_name" />
      <x-input-error :messages="$errors->get('activity_name')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="ordinace_name" :value="__('Ordinace Name')" />
      <x-text-input id="ordinace_name" type="text" wire:model="ordinace_name" placeholder="{{ __('Enter Ordinace Name') }}" name="ordinace_name" class="w-full mt-1" :value="old('ordinace_name', $ordinace_name)" autocomplete="ordinace_name" />
      <x-input-error :messages="$errors->get('ordinace_name')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="status" :value="__('Status')" /> <br>
      <x-input-checkbox id="status"  wire:model="status"  name="status" :value="old('status',$status)" />
      <x-input-label for="status" class="inline mb-1 mx-2" :value="__('Make In Active')" />
      <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
  </div>
  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
