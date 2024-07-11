<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Time Table Slot
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="start_time" :value="__('Start Time')" />
      <x-text-input id="start_time" type="time" wire:model.live="start_time"  name="start_time" class="w-full mt-1" :value="old('start_time', $start_time)" autocomplete="start_time" />
      <x-input-error :messages="$errors->get('start_time')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="end_time" :value="__('End Time')" />
      <x-text-input id="end_time" type="time" wire:model.live="end_time"  name="end_time" class="w-full mt-1" :value="old('end_time', $end_time)" autocomplete="end_time" />
      <x-input-error :messages="$errors->get('end_time')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="timeslot" :value="__('Time Slot')" />
      <x-text-input id="timeslot" type="text" wire:model="timeslot" placeholder="{{ __('Enter Time Slot') }}" name="timeslot" class="w-full mt-1" :value="old('timeslot', $timeslot)" autocomplete="timeslot" />
      <x-input-error :messages="$errors->get('timeslot')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="slot" :value="__('Slot')" />
      <x-text-input id="slot" type="number" wire:model="slot" placeholder="{{ __('Enter Slot') }}" name="slot" class="w-full mt-1" :value="old('slot', $slot)" autocomplete="slot" />
      <x-input-error :messages="$errors->get('slot')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="isactive" :value="__('Status')" /> <br>
      <x-input-checkbox id="isactive"  wire:model="isactive"  name="isactive" :value="old('isactive',$isactive)" />
      <x-input-label for="isactive" class="inline mb-1 mx-2" :value="__('Make In Active')" />
      <x-input-error :messages="$errors->get('isactive')" class="mt-2" />
    </div>
  </div>
  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
