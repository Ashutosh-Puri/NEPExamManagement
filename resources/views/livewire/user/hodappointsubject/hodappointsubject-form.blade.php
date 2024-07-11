<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        HOD Appointment Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="faculty_id" :value="__('Faculty Name')" />
            <x-input-select id="faculty_id" wire:model="faculty_id" name="faculty_id" class="text-center @error('faculty_id') is-invalid @enderror w-full mt-1" :value="old('faculty_id', $faculty_id)" required autofocus autocomplete="faculty_id">
            <x-select-option class="text-start" hidden> -- Select Faculty -- </x-select-option>
                @forelse ($faculties as $facultyid => $facultyname)
                    <x-select-option wire:key="{{ $facultyid }}" value="{{ $facultyid }}" class="text-start"> {{ $facultyname }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Faculties Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('faculty_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="course_id" :value="__('Course')" />
            <x-input-select id="course_id" wire:model.live="course_id" name="course_id" class="text-center w-full mt-1" :value="old('course_id', $course_id)" required autocomplete="course_id">
                <x-select-option class="text-start" hidden> -- Select Course -- </x-select-option>
                @forelse ($courses as $courseid => $coursename)
                    <x-select-option wire:key="{{ $courseid }}" value="{{ $courseid }}" class="text-start"> {{ $coursename }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Courses Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="patternclass_id" :value="__('Select Class')" />
            <x-input-select id="patternclass_id" wire:model.live="patternclass_id" name="patternclass_id" class="text-center w-full mt-1" :value="old('patternclass_id', $patternclass_id)" required autocomplete="patternclass_id">
                <x-select-option class="text-start" hidden> -- Select Class -- </x-select-option>
                @forelse ($pattern_classes as $pattern_class)
                    <x-select-option wire:key="{{ $pattern_class->id }}" value="{{ $pattern_class->id }}" class="text-start"> {{ $pattern_class->classyear_name ?? '-' }} {{ $pattern_class->course_name ?? '-' }} {{ $pattern_class->pattern_name ?? '-' }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Pattern Classes Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="courseclass_subject_id" :value="__('Subject Name')" />
            <x-input-select id="courseclass_subject_id" wire:model="courseclass_subject_id" name="courseclass_subject_id" class="text-center @error('courseclass_subject_id') is-invalid @enderror w-full mt-1" :value="old('courseclass_subject_id', $courseclass_subject_id)" required autofocus autocomplete="courseclass_subject_id">
                <x-select-option class="text-start" hidden> -- Select Subject -- </x-select-option>
                @forelse ($courseclass_subjects as $courseclass_subject)
                    <x-select-option wire:key="{{ $courseclass_subject->id }}" value="{{ $courseclass_subject->id }}" class="text-start"> {{ $courseclass_subject->subject_name }} </x-select-option>
                @empty
                    <x-select-option class="text-start"> Subject Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('courseclass_subject_id')" class="mt-2" />
        </div>
    </div>
    <x-form-btn>Submit</x-form-btn>
  </div>
