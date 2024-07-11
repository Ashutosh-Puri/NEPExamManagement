<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        @if($mode == 'add')
            <span>Create Exam Panel</span>
        @elseif($mode == 'edit')
           <span>Edit Exam Panel</span>
           <x-table.download i="0" class="float-end text-xs text-white" wire:click="setmode('add')">Back</x-table.download>
        @endif
    </div>
    <x-form wire:submit="save()">
        <div class="grid grid-cols-1 md:grid-cols-1">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="patternclass_id" :value="__('Select Class')" />
                <x-input-select id="patternclass_id" wire:model.live="patternclass_id" name="patternclass_id" class="text-center w-full mt-1" :value="old('patternclass_id', $patternclass_id)" required autocomplete="patternclass_id">
                    <x-select-option class="text-start" hidden> -- Select Class -- </x-select-option>
                    @forelse ($pattern_classes as $pattern_calss)
                        <x-select-option wire:key="{{ $pattern_calss->id }}" value="{{ $pattern_calss->id }}" class="text-start"> {{ $pattern_calss->classyear_name ?? '-' }} {{ $pattern_calss->course_name ?? '-' }} {{ $pattern_calss->pattern_name ?? '-' }}  </x-select-option>
                    @empty
                        <x-select-option class="text-start">Pattern Classes Not Found</x-select-option>
                    @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_id" :value="__('Subject Name')" />
                <x-input-select id="subject_id" wire:model.live="subject_id" name="subject_id" class="text-center @error('subject_id') is-invalid @enderror w-full mt-1" required autofocus autocomplete="subject_id">
                    <x-select-option class="text-start" hidden> -- Select Subject -- </x-select-option>
                    @forelse ($subjects as $subject)
                        <x-select-option wire:key="{{ $subject->id }}" value="{{ $subject->id }}" class="text-start" :selected="$subject->id == $subject_id">
                            {{ $subject->subject_name }}
                        </x-select-option>
                    @empty
                        <x-select-option class="text-start"> Subject Not Found</x-select-option>
                    @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="post_id" :value="__('Select Post')" />
                <x-input-select id="post_id" wire:model="post_id" name="post_id" class="text-center @error('post_id') is-invalid @enderror w-full mt-1" :value="old('post_id', $post_id)" required autofocus autocomplete="post_id">
                    <x-select-option class="text-start" hidden> -- Select Post -- </x-select-option>
                    @forelse ($posts as $post)
                        <x-select-option wire:key="{{ $post->id }}" value="{{ $post->id }}" class="text-start">{{ $post->post_name }}</x-select-option>
                    @empty
                        <x-select-option class="text-start">Post Not Found</x-select-option>
                    @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('post_id')" class="mt-2" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="department_id" :value="__('Select Department')" />
                <x-input-select id="department_id" wire:model.live="department_id" name="department_id" class="text-center @error('department_id') is-invalid @enderror w-full mt-1" :value="old('department_id', $department_id)" required autofocus autocomplete="department_id">
                    <x-select-option class="text-start" hidden> -- Select Department -- </x-select-option>
                    @forelse ($departments as $department)
                        <x-select-option wire:key="{{ $department->id }}" value="{{ $department->id }}" class="text-start">{{ $department->dept_name }}</x-select-option>
                    @empty
                        <x-select-option class="text-start">Department Not Found</x-select-option>
                    @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="faculty_id" :value="__('Select Faculties')" />
                <x-input-select multiple id="faculty_id" wire:model.live="selected_faculties" name="faculty_id" class="text-center h-56 @error('selected_faculties') is-invalid @enderror w-full mt-1" required autofocus autocomplete="faculty_id">
                    @foreach ($faculties as $faculty)
                        <x-select-option wire:key="{{ $faculty->id }}" value="{{ $faculty->id }}" class="text-start">{{ $faculty->faculty_name }}</x-select-option>
                    @endforeach
                </x-input-select>
                <x-input-error :messages="$errors->get('selected_faculties')" class="mt-2" />
            </div>
        </div>
        <x-form-btn>Submit</x-form-btn>
    </x-form>
</div>
