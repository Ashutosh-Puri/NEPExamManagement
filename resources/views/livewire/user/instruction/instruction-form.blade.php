<div>
    <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
        <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
            Instruction
        </div>
        <div class="grid grid-cols-1 md:grid-cols-1">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="instruction_name" :value="__('Instruction')" />
                <x-required />
                <x-textarea id="instruction_name" type="text" wire:model="instruction_name" name="instruction_name" class="w-full mt-1" :value="old('instruction_name',$instruction_name)" required autofocus autocomplete="instruction_name" />
                <x-input-error :messages="$errors->get('instruction_name')" class="mt-2" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2">

            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="instructiontype_id" :value="__('InstructionType')" />
                <x-required />
                <x-input-select id="instructiontype_id" wire:model="instructiontype_id" name="instructiontype_id" class="text-center w-full mt-1" :value="old('instructiontype_id',$instructiontype_id)" required autofocus autocomplete="instructiontype_id">
                    <x-select-option class="text-start" hidden> -- Select Instruction Type -- </x-select-option>
                    @foreach ($inst as $i_id =>$iname)
                    <x-select-option wire:key="{{ $i_id }}" value="{{ $i_id }}" class="text-start">{{ $iname }}</x-select-option>
                    @endforeach
                </x-input-select>
                <x-input-error :messages="$errors->get('instructiontype_id')" class="mt-2" />
            </div>

            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="college_id" :value="__('College')" />
                <x-required />
                <x-input-select id="college_id" wire:model="college_id" name="college_id" class="text-center w-full mt-1" :value="old('college_id',$college_id)" required autofocus autocomplete="college_id">
                    <x-select-option class="text-start" hidden> -- Select College -- </x-select-option>
                    @foreach ($colleges as $c_id =>$cname)
                    <x-select-option wire:key="{{ $c_id }}" value="{{ $c_id }}" class="text-start">{{ $cname }}</x-select-option>
                    @endforeach
                </x-input-select>
                <x-input-error :messages="$errors->get('college_id')" class="mt-2" />
            </div>

            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="is_active" :value="__('Status')" />
                <x-required />
                <x-input-select id="is_active" wire:model="is_active" name="is_active" class="text-center  w-full mt-1" :value="old('is_active',$is_active)" required autocomplete="is_active">
                    <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
                    <x-select-option class="text-start" value="1">Active</x-select-option>
                    <x-select-option class="text-start" value="0">Inactive</x-select-option>
                </x-input-select>
                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
            </div>
        </div>
        <x-form-btn >
            Submit
        </x-form-btn>
    </div>
</div>