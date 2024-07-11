<div>
  <div>
    <x-card-header heading="Delete Exam Form Before Inward"/>
    <x-form wire:submit="delete()">
      <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
          Exam Form Regular Inward Process For Delete Exam Form
        </div>
        <div class="px-5 py-2 text-sm col-span-1  text-gray-600 dark:text-gray-400">
            <x-input-label for="examformmasterid" :value="__('Enter Application ID')" />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-12 mb-4">
          <div class="px-5 py-2 text-sm md:col-span-1 text-gray-600 dark:text-gray-400">
            <x-input-show value="1606"/>
          </div>
          <div class="px-5 py-2 text-sm md:col-span-8  text-gray-600 dark:text-gray-400">
            <x-text-input id="examformmasterid" type="text" wire:model="examformmasterid" placeholder="{{ __('Enter Application ID') }}" name="examformmasterid" class="w-full mt-1" :value="old('examformmasterid', $examformmasterid)" autocomplete="examformmasterid" />
            <x-input-error :messages="$errors->get('examformmasterid')" class="mt-1" />
          </div>
          <div class="px-5 py-2 text-sm md:col-span-3  text-gray-600 dark:text-gray-400">
              <x-form-btn wire:loading.attr="disabled">Delete Exam Form</x-form-btn>
          </div>
        </div>
      </div>
    </x-form>
  </div>
</div>
