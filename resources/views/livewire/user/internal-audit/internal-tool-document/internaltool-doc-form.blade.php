<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Internal Tool Document Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="internaltoolmaster_id" :value="__('Internal Tool Name')" />
            <x-input-select id="internaltoolmaster_id" wire:model="internaltoolmaster_id" name="internaltoolmaster_id" class="text-center w-full mt-1" :value="old('internaltoolmaster_id', $internaltoolmaster_id)" required autocomplete="internaltoolmaster_id">
                <x-select-option class="text-start" hidden> -- Select Tool -- </x-select-option>
                @forelse ($internaltoolmasters as $internaltoolid => $internaltoolname)
                    <x-select-option wire:key="{{ $internaltoolid }}" value="{{ $internaltoolid }}" class="text-start"> {{ $internaltoolname }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Tools Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('internaltoolmaster_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="internaltooldoc_id" :value="__('Internal Tool Document')" />
            <x-input-select id="internaltooldoc_id" wire:model="internaltooldoc_id" name="internaltooldoc_id" class="text-center w-full mt-1" :value="old('internaltooldoc_id', $internaltooldoc_id)" required autocomplete="internaltooldoc_id">
                <x-select-option class="text-start" hidden> -- Select Document -- </x-select-option>
                @forelse ($internaltooldocuments as $internaltooldocid => $internaltooldocname)
                    <x-select-option wire:key="{{ $internaltooldocid }}" value="{{ $internaltooldocid }}" class="text-start"> {{ $internaltooldocname }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Documents Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('internaltooldoc_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <br>
            <x-input-checkbox id="is_multiple"  wire:model="is_multiple"  name="is_multiple" :value="old('is_multiple',$is_multiple)" />
            <x-input-label for="is_multiple" class="inline mb-1 mx-2" :value="__('Is Multiple')" />
            <x-input-error :messages="$errors->get('is_multiple')" class="mt-2" />
          </div>
    </div>
    <x-form-btn>Submit</x-form-btn>
</div>
