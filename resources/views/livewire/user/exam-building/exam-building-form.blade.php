<div>
    <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
        <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
            Exam Building
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3">

            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="exam_id" :value="__('Exam')" />
                <x-required />
                <x-input-select id="exam_id" wire:model="exam_id" name="exam_id" class="text-center w-full mt-1" :value="old('exam_id',$exam_id)" required autofocus autocomplete="exam_id">
                    <x-select-option class="text-start" hidden> -- Select Exam -- </x-select-option>
                    @foreach ($exam as $e_id=>$ename)
                    <x-select-option wire:key="{{ $e_id }}" value="{{ $e_id }}" class="text-start">{{$ename }}</x-select-option>
                    @endforeach
                </x-input-select>
                <x-input-error :messages="$errors->get('exam_id')" class="mt-2" />
            </div>

            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="building_id" :value="__('Building')" />
                <x-required />
                <x-input-select id="building_id" wire:model="building_id" name="building_id" class="text-center w-full mt-1" :value="old('building_id',$building_id)" required autofocus autocomplete="building_id">
                    <x-select-option class="text-start" hidden> -- Select Building -- </x-select-option>
                    @foreach ($building as $b_id=>$bname)
                    <x-select-option wire:key="{{ $b_id }}" value="{{ $b_id }}" class="text-start">{{$bname }}</x-select-option>
                    @endforeach
                </x-input-select>
                <x-input-error :messages="$errors->get('building_id')" class="mt-2" />
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
</div>
