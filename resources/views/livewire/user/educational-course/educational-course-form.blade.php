<div>
    <section>
        <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
            <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
                Educational Course
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="course_name" :value="__('Course Name')" />
                    <x-required />
                    <x-text-input id="course_name" type="text" wire:model="course_name" name="course_name" class="w-full mt-1" :value="old('course_name',$course_name)" required autofocus autocomplete="course_name" />
                    <x-input-error :messages="$errors->get('course_name')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="programme_id" :value="__('Program')" />
                    <x-required />
                    <x-input-select id="programme_id" wire:model="programme_id" name="programme_id" class="text-center w-full mt-1" :value="old('programme_id',$programme_id)" required autofocus autocomplete="programme_id">
                        <x-select-option class="text-start" hidden> -- Select Program -- </x-select-option>
                        @foreach ($programs as $p_id=>$pname)
                        <x-select-option wire:key="{{ $p_id }}" value="{{ $p_id }}" class="text-start">{{$pname }}</x-select-option>
                        @endforeach
                    </x-input-select>
                    <x-input-error :messages="$errors->get('programme_id')" class="mt-2" />
                </div>
                
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="is_active" :value="__('Status')" />
                    <x-required />
                    <x-input-select id="is_active" wire:model="is_active" name="is_active" class="text-center  w-full mt-1" :value="old('is_active',$is_active)" required autocomplete="is_active">
                        <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
                        <x-select-option class="text-start" value="0">Inactive</x-select-option>
                        <x-select-option class="text-start" value="1">Active</x-select-option>
                    </x-input-select>
                    <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                </div>
            </div>
            <div class="h-20 p-2">
                <x-form-btn wire:target="college_logo_path" wire:loading.attr="disable">
                    Submit
                </x-form-btn>
            </div>
        </div>
    </section>
</div>
