<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Subject Vertical Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_vertical" :value="__('Subject Category')" />
            <x-text-input id="subject_vertical" type="text" wire:model="subject_vertical" name="subject_vertical" placeholder="Subject Category" class=" @error('subject_vertical') is-invalid @enderror w-full mt-1" :value="old('subject_vertical', $subject_vertical)" required autofocus autocomplete="subject_vertical" />
            <x-input-error :messages="$errors->get('subject_vertical')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectvertical_shortname" :value="__('Subject Category Shortname')" />
            <x-text-input id="subjectvertical_shortname" type="text" wire:model="subjectvertical_shortname" name="subjectvertical_shortname" placeholder="Subject Category Shortname" class=" @error('subjectvertical_shortname') is-invalid @enderror w-full mt-1" :value="old('subjectvertical_shortname', $subjectvertical_shortname)" required autofocus autocomplete="subjectvertical_shortname" />
            <x-input-error :messages="$errors->get('subjectvertical_shortname')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectbuckettype_id" :value="__('Subject Bucket Type')" />
            <x-input-select id="subjectbuckettype_id" wire:model.live="subjectbuckettype_id" name="subjectbuckettype_id" class="text-center @error('subjectbuckettype_id') is-invalid @enderror w-full mt-1" :value="old('subjectbuckettype_id', $subjectbuckettype_id)" required autofocus autocomplete="subjectbuckettype_id">
                <x-select-option class="text-start" hidden> -- Select Subject Bucket Type -- </x-select-option>
                @forelse ($subjectbucket_types as $subjectbuckettypeid => $subjectbuckettypename)
                    <x-select-option wire:key="{{ $subjectbuckettypeid }}" value="{{ $subjectbuckettypeid }}" class="text-start">{{ $subjectbuckettypename }}</x-select-option>
                @empty
                    <x-select-option class="text-start">Bucket Types Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('subjectbuckettype_id')" class="mt-2" />
        </div>
    </div>
    <x-form-btn>Submit</x-form-btn>
</div>
