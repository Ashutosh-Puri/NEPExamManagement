<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Internal Tool Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-1">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="toolname" :value="__('Tool Name')" />
            <x-text-input id="toolname" type="text" wire:model="toolname" name="toolname" placeholder="Tool Name" class=" @error('toolname') is-invalid @enderror w-full mt-1" :value="old('toolname', $toolname)" required autofocus autocomplete="toolname" />
            <x-input-error :messages="$errors->get('toolname')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="course_type" :value="__('Programme Type')" />
            <x-input-select id="course_type" wire:model="course_type" name="course_type" class="text-center w-full mt-1" :value="old('course_type', $course_type)" required autocomplete="course_type">
                <x-select-option class="text-start" hidden> -- Select Programme -- </x-select-option>
                @forelse ($course_types as $coursetypeid => $coursetypename)
                    <x-select-option wire:key="{{ $coursetypeid }}" value="{{ $coursetypename }}" class="text-start"> {{ $coursetypename }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Programme Types Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('course_type')" class="mt-2" />
        </div>
    </div>
    <x-form-btn>Submit</x-form-btn>
</div>
