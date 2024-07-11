<div>
    <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
        <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
            Exam Post Order
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="post_name" :value="__('Post Name')" />
                <x-required />
                <x-text-input id="post_name" type="text" wire:model="post_name" name="post_name" class="w-full mt-1" :value="old('post_name',$post_name)" required autofocus autocomplete="post_name" />
                <x-input-error :messages="$errors->get('post_name')" class="mt-2" />
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
