<div>
    <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
        <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
            Block
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="building_id" :value="__('Building')" />
                <x-required />
                <x-input-select id="building_id" wire:model="building_id" name="building_id" class="text-center w-full mt-1" :value="old('building_id',$building_id)" required autofocus autocomplete="building_id">
                    <x-select-option class="text-start" hidden> -- Select Building -- </x-select-option>
                    @foreach ($buildings as $s_id=>$bname)
                    <x-select-option wire:key="{{ $s_id }}" value="{{ $s_id }}" class="text-start">{{$bname }}</x-select-option>
                    @endforeach
                </x-input-select>
                <x-input-error :messages="$errors->get('building_id')" class="mt-2" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="classname" :value="__('Class Name')" />
                <x-required />
                <x-text-input id="classname" type="text" wire:model="classname" name="classname" class="w-full mt-1" :value="old('classname',$classname)" required autofocus autocomplete="classname" />
                <x-input-error :messages="$errors->get('classname')" class="mt-2" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="block" :value="__('Block Name')" />
                <x-required />
                <x-text-input id="block" type="text" wire:model="block" name="block" class="w-full mt-1" :value="old('block',$block)" required autofocus autocomplete="block" />
                <x-input-error :messages="$errors->get('block')" class="mt-2" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="capacity" :value="__('Capacity')" />
                <x-required />
                <x-text-input id="capacity" type="number" wire:model="capacity" name="capacity" class="w-full mt-1" :value="old('capacity',$capacity)" required autofocus autocomplete="capacity" />
                <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="noofblocks" :value="__('No of Blocks')" />
                <x-required />
                <x-text-input id="noofblocks" type="number" wire:model="noofblocks" name="noofblocks" class="w-full mt-1" :value="old('noofblocks',$noofblocks)" required autofocus autocomplete="noofblocks" />
                <x-input-error :messages="$errors->get('noofblocks')" class="mt-2" />
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
         <div class="h-20 p-2">
                <x-form-btn>
                    Submit
                </x-form-btn>
            </div>
    </div>
