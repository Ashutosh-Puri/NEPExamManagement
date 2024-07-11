<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
   Class Year
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="classyear_name" :value="__('Class Year Name')" />
      <x-text-input id="classyear_name" type="text" wire:model="classyear_name" placeholder="{{ __('Enter Class Year Name') }}" name="classyear_name" class="w-full mt-1" :value="old('classyear_name', $classyear_name)" autocomplete="classyear_name" />
      <x-input-error :messages="$errors->get('classyear_name')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="class_degree_name" :value="__('Class Degree Name')" />
      <x-text-input id="class_degree_name" type="text" wire:model="class_degree_name" placeholder="{{ __('Enter Class Degree Name') }}" name="class_degree_name" class="w-full mt-1" :value="old('class_degree_name', $class_degree_name)" autocomplete="class_degree_name" />
      <x-input-error :messages="$errors->get('class_degree_name')" class="mt-1" />
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
