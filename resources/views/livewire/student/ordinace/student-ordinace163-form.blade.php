<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Apply For Ordinace 163
  </div>
  <x-form wire:submit="add()">
    <div class="grid grid-cols-1 md:grid-cols-2">
      <div class="px-5 py-2 col-span-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="ordinace163master_id" :value="__('Select Activity')" />
        <x-input-select id="ordinace163master_id" wire:model="ordinace163master_id" name="ordinace163master_id" class="text-center w-full mt-1" :value="old('ordinace163master_id', $ordinace163master_id)" required autocomplete="ordinace163master_id">
          <x-select-option class="text-start" hidden> -- Select Activity -- </x-select-option>
          @forelse ($ordinace_163s as $ordinace_163id => $ordinace_163name)
            <x-select-option wire:key="{{ $ordinace_163id }}" value="{{ $ordinace_163id }}" class="text-start"> {{ $ordinace_163name }} </x-select-option>
          @empty
            <x-select-option class="text-start">Activities Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('ordinace163master_id')" class="mt-1" />
      </div>
    </div>
    <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
  </x-form>
</div>
