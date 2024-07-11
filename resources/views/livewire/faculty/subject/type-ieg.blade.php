<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Subject Marks Details
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_maxmarks" :value="__('Maximum Marks')" />
            <x-text-input id="subject_maxmarks" type="number" wire:model="subject_maxmarks" name="subject_maxmarks" placeholder="Maximum Marks" class=" @error('subject_maxmarks') is-invalid @enderror w-full mt-1" :value="old('subject_maxmarks', $subject_maxmarks)" required autofocus autocomplete="subject_maxmarks" />
            <x-input-error :messages="$errors->get('subject_maxmarks')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_maxmarks_int" :value="__('Internal Maximum Marks')" />
            <x-text-input id="subject_maxmarks_int" type="number" wire:model="subject_maxmarks_int" name="subject_maxmarks_int" placeholder="Maximum Marks" class=" @error('subject_maxmarks_int') is-invalid @enderror w-full mt-1" :value="old('subject_maxmarks_int', $subject_maxmarks_int)" required autofocus autocomplete="subject_maxmarks_int" />
            <x-input-error :messages="$errors->get('subject_maxmarks_int')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_maxmarks_ext" :value="__('External Maximum Marks')" />
            <x-text-input id="subject_maxmarks_ext" type="number" wire:model="subject_maxmarks_ext" name="subject_maxmarks_ext" placeholder="External Maximum Marks" class=" @error('subject_maxmarks_ext') is-invalid @enderror w-full mt-1" :value="old('subject_maxmarks_ext', $subject_maxmarks_ext)" required autofocus autocomplete="subject_maxmarks_ext" />
            <x-input-error :messages="$errors->get('subject_maxmarks_ext')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_intpassing" :value="__('Internal Passing Marks')" />
            <x-text-input id="subject_intpassing" type="number" wire:model="subject_intpassing" name="subject_intpassing" placeholder="Internal Passing Marks" class=" @error('subject_intpassing') is-invalid @enderror w-full mt-1" :value="old('subject_intpassing', $subject_intpassing)" required autofocus autocomplete="subject_intpassing" />
            <x-input-error :messages="$errors->get('subject_intpassing')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_extpassing" :value="__('External Passing Marks')" />
            <x-text-input id="subject_extpassing" type="number" wire:model="subject_extpassing" name="subject_extpassing" placeholder="Internal Practical Passing Marks" class=" @error('subject_extpassing') is-invalid @enderror w-full mt-1" :value="old('subject_extpassing', $subject_extpassing)" required autofocus autocomplete="subject_extpassing" />
            <x-input-error :messages="$errors->get('subject_extpassing')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_totalpassing" :value="__('Total Passing Marks')" />
            <x-text-input id="subject_totalpassing" type="number" wire:model="subject_totalpassing" name="subject_totalpassing" placeholder="Total Passing Marks" class=" @error('subject_totalpassing') is-invalid @enderror w-full mt-1" :value="old('subject_totalpassing', $subject_totalpassing)" required autofocus autocomplete="subject_totalpassing" />
            <x-input-error :messages="$errors->get('subject_totalpassing')" class="mt-2" />
        </div>
    </div>
    <x-form-btn>Submit</x-form-btn>
</div>
