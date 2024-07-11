<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Department Prefix Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="dept_id" :value="__('Department')" />
            <x-input-select id="dept_id" wire:model.live="dept_id" name="dept_id" class="text-center @error('dept_id') is-invalid @enderror w-full mt-1" :value="old('dept_id', $dept_id)" required autofocus autocomplete="dept_id">
                <x-select-option class="text-start" hidden> -- Select Department -- </x-select-option>
                @forelse ($departments as $departmentid => $departmentname )
                    <x-select-option wire:key="{{ $departmentid }}" value="{{ $departmentid }}" class="text-start"> {{ $departmentname }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Departments Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('dept_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="pattern_id" :value="__('Pattern')" />
            <x-input-select id="pattern_id" wire:model.live="pattern_id" name="pattern_id" class="text-center @error('pattern_id') is-invalid @enderror w-full mt-1" :value="old('pattern_id', $pattern_id)" required autofocus autocomplete="pattern_id">
                <x-select-option class="text-start" hidden> -- Select Pattern -- </x-select-option>
                @forelse ($patterns as $patternid => $patternname )
                    <x-select-option wire:key="{{ $patternid }}" value="{{ $patternid }}" class="text-start"> {{ $patternname }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Patterns Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('pattern_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="prefix" :value="__('Department Prefix')" />
            <x-text-input id="prefix" type="text" wire:model="prefix" name="prefix" placeholder="Department Prefix" class=" @error('prefix') is-invalid @enderror w-full mt-1" :value="old('prefix', $prefix)" required autofocus autocomplete="prefix" oninput="this.value = this.value.toUpperCase()" />
            <x-input-error :messages="$errors->get('prefix')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="postfix" :value="__('Department Postfix')" />
            <x-text-input id="postfix" type="text" wire:model="postfix" name="postfix" placeholder="Department Postfix" class=" @error('postfix') is-invalid @enderror w-full mt-1" :value="old('postfix', $postfix)" required autofocus autocomplete="postfix" oninput="this.value = this.value.toUpperCase()" />
            <x-input-error :messages="$errors->get('postfix')" class="mt-2" />
        </div>
    </div>
    <x-form-btn>Submit</x-form-btn>
</div>
