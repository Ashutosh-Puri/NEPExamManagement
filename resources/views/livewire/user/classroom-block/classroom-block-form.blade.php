<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
     Classroom Block
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2">

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="classroom_id" :value="__('Classroom')" />
            <x-required />
            <x-input-select id="classroom_id" wire:model="classroom_id" name="classroom_id" class="text-center w-full mt-1" :value="old('classroom_id',$classroom_id)" required autofocus autocomplete="classroom_id">
                <x-select-option class="text-start" hidden> -- Select Classroom -- </x-select-option>
                @foreach ($classroom as $b_id=>$bname)
                <x-select-option wire:key="{{ $b_id}}" value="{{ $b_id }}" class="text-start">{{ $bname }}</x-select-option>
                @endforeach
            </x-input-select>
            <x-input-error :messages="$errors->get('classroom_id')" class="mt-2" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="blockmaster_id" :value="__('Block')" />
            <x-required />
            <x-input-select id="blockmaster_id" wire:model="blockmaster_id" name="blockmaster_id" class="text-center w-full mt-1" :value="old('blockmaster_id',$blockmaster_id)" required autofocus autocomplete="blockmaster_id">
                <x-select-option class="text-start" hidden> -- Select Block -- </x-select-option>
                @foreach ($blocks as $b_id=>$bname)
                <x-select-option wire:key="{{ $b_id}}" value="{{ $b_id }}" class="text-start">{{ $bname }}</x-select-option>
                @endforeach
            </x-input-select>
            <x-input-error :messages="$errors->get('blockmaster_id')" class="mt-2" />
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
  