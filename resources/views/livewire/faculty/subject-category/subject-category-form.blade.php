<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Subject Category Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectcategory" :value="__('Subject Category')" />
            <x-text-input id="subjectcategory" type="text" wire:model="subjectcategory" name="subjectcategory" placeholder="Subject Category" class=" @error('subjectcategory') is-invalid @enderror w-full mt-1" :value="old('subjectcategory', $subjectcategory)" required autofocus autocomplete="subjectcategory" />
            <x-input-error :messages="$errors->get('subjectcategory')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectcategory_shortname" :value="__('Subject Category Shortname')" />
            <x-text-input id="subjectcategory_shortname" type="text" wire:model="subjectcategory_shortname" name="subjectcategory_shortname" placeholder="Subject Category Shortname" class=" @error('subjectcategory_shortname') is-invalid @enderror w-full mt-1" :value="old('subjectcategory_shortname', $subjectcategory_shortname)" required autofocus autocomplete="subjectcategory_shortname" />
            <x-input-error :messages="$errors->get('subjectcategory_shortname')" class="mt-2" />
        </div>
    </div>
    <x-form-btn>Submit</x-form-btn>
</div>
