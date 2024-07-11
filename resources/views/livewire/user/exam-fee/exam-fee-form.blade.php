<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Exam Fee
  </div>
  <div class="grid grid-cols-1 md:grid-cols-4">
    <div class="px-5 py-2 col-span-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="fee_name" :value="__('Fee Name')" />
      <x-text-input id="fee_name" type="text" wire:model="fee_name" placeholder="{{ __('Enter Fee Name') }}" name="fee_name" class="w-full mt-1" :value="old('fee_name', $fee_name)" autocomplete="fee_name" />
      <x-input-error :messages="$errors->get('fee_name')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="default_professional_fee" :value="__('Default Professional Course Fee')" />
      <x-text-input id="default_professional_fee" type="number" wire:model="default_professional_fee" placeholder="{{ __('Enter Professional Course Fee') }}" name="default_professional_fee" class="w-full mt-1" :value="old('default_professional_fee', $default_professional_fee)" autocomplete="default_professional_fee" />
      <x-input-error :messages="$errors->get('default_professional_fee')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="default_non_professional_fee" :value="__('Default Non Professional Course Fee')" />
      <x-text-input id="default_non_professional_fee" type="number" wire:model="default_non_professional_fee" placeholder="{{ __('Enter Non Professional Course Fee') }}" name="default_non_professional_fee" class="w-full mt-1" :value="old('default_non_professional_fee', $default_non_professional_fee)" autocomplete="default_non_professional_fee" />
      <x-input-error :messages="$errors->get('default_non_professional_fee')" class="mt-1" />
    </div>
    <div class="px-5  py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="form_type_id" :value="__('Select Form Type')" />
      <x-input-select id="form_type_id" wire:model.live="form_type_id" name="form_type_id" class="text-center w-full mt-1" :value="old('form_type_id', $form_type_id)"  autocomplete="form_type_id">
        <x-select-option class="text-start" hidden> -- Select Form Type -- </x-select-option>
        @forelse ($formtypes as $formtypeid => $formtypename)
          <x-select-option wire:key="{{ $formtypeid }}" value="{{ $formtypeid }}" class="text-start"> {{ $formtypename ?? '-' }}</x-select-option>
        @empty
          <x-select-option class="text-start">Form Types Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('form_type_id')" class="mt-1" />
    </div>
    <div class="px-5  py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="apply_fee_id" :value="__('Select Apply Fee Type')" />
      <x-input-select id="apply_fee_id" wire:model.live="apply_fee_id" name="apply_fee_id" class="text-center w-full mt-1" :value="old('apply_fee_id', $apply_fee_id)"  autocomplete="apply_fee_id">
        <x-select-option class="text-start" hidden> -- Select Apply Fee Type -- </x-select-option>
        @forelse ($applyfees as $applyfeeid => $applyfeename)
          <x-select-option wire:key="{{ $applyfeeid }}" value="{{ $applyfeeid }}" class="text-start"> {{ $applyfeename ?? '-' }}</x-select-option>
        @empty
          <x-select-option class="text-start">Apply Fee Types Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('apply_fee_id')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm  text-gray-600 dark:text-gray-400">
      <x-input-label for="remark" :value="__('Remark')" />
      <x-text-input id="remark" type="text" wire:model="remark" placeholder="{{ __('Enter Remark') }}" name="remark" class="w-full mt-1" :value="old('remark', $remark)" autocomplete="remark" />
      <x-input-error :messages="$errors->get('remark')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="active_status" :value="__('Status')" /> <br>
      <x-input-checkbox id="active_status" wire:model="active_status" name="active_status" :value="old('active_status', $active_status)" />
      <x-input-label for="active_status" class="inline mb-1 mx-2" :value="__('Make In Active')" />
      <x-input-error :messages="$errors->get('active_status')" class="mt-2" />
    </div>
  </div>
  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
