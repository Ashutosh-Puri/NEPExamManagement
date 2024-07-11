<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Notice
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="title" :value="__('Title')" />
      <x-text-input id="title" type="text" wire:model="title" placeholder="{{ __('Title') }}" name="title" class="w-full mt-1" :value="old('title', $title)" autocomplete="title" />
      <x-input-error :messages="$errors->get('title')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="type" :value="__('Type')" />
      <x-input-select id="type" wire:model.live="type" name="type" class="text-center w-full mt-1" :value="old('type', $type)" required autocomplete="type">
        <x-select-option class="text-start" hidden>-- Select Status --</x-select-option>
        <x-select-option class="text-start" value="0">Guest</x-select-option>
        <x-select-option class="text-start" value="1">Student</x-select-option>
        <x-select-option class="text-start" value="2">Faculty</x-select-option>
        <x-select-option class="text-start" value="3">User</x-select-option>
        <x-select-option class="text-start" value="4">All</x-select-option>
      </x-input-select>
      <x-input-error :messages="$errors->get('type')" class="mt-1" />
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
      <x-input-label for="description" :value="__('Description')" />
      <x-textarea id="description" wire:model="description" placeholder="{{ __('Description') }}" name="description" class="w-full mt-1" :value="old('description', $description)" autocomplete="description" />
      <x-input-error :messages="$errors->get('description')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="is_active" :value="__('Status')" /> <br>
      <x-input-checkbox id="is_active" wire:model="is_active" name="is_active" :value="old('is_active', $is_active)" />
      <x-input-label for="is_active" class="inline mb-1 mx-2" :value="__('Make In Active')" />
      <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
    </div>
  </div>
  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
