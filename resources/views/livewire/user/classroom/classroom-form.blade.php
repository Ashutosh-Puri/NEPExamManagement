<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
     Classroom
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="building_id" :value="__('Building')" />
            <x-required />
            <x-input-select id="building_id" wire:model="building_id" name="building_id" class="text-center w-full mt-1" :value="old('building_id',$building_id)" required autofocus autocomplete="building_id">
                <x-select-option class="text-start" hidden> -- Select Building -- </x-select-option>
                @foreach ($building as $b_id=>$bname)
                <x-select-option wire:key="{{ $b_id}}" value="{{ $b_id }}" class="text-start">{{ $bname }}</x-select-option>
                @endforeach
            </x-input-select>
            <x-input-error :messages="$errors->get('building_id')" class="mt-2" />
        </div>
    
      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="class_name" :value="__('Class Name')" />
        <x-required />
        <x-text-input id="class_name" type="text" wire:model="class_name"  name="class_name" class="w-full mt-1" :value="old('class_name', $class_name)" autocomplete="class_name" />
        <x-input-error :messages="$errors->get('class_name')" class="mt-1" />
      </div>

      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="noofbenches" :value="__('No of Benches')" />
        <x-required />
        <x-text-input id="noofbenches" type="number" wire:model="noofbenches"  name="noofbenches" class="w-full mt-1" :value="old('noofbenches', $noofbenches)" autocomplete="noofbenches" />
        <x-input-error :messages="$errors->get('noofbenches')" class="mt-1" />
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
  