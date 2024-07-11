<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
       Subject Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectvertical_id" :value="__('Subject Vertical')" />
            <x-input-select id="subjectvertical_id" wire:model.live="subjectvertical_id" name="subjectvertical_id" class="text-center @error('subjectvertical_id') is-invalid @enderror w-full mt-1" :value="old('subjectvertical_id', $subjectvertical_id)" required autofocus autocomplete="subjectvertical_id">
                <x-select-option class="text-start" hidden> -- Select Subject Vertical -- </x-select-option>
                @forelse ($subject_verticals as $subject_vertical)
                    <x-select-option wire:key="{{ $subject_vertical->id }}" value="{{ $subject_vertical->id }}" class="text-start"> {{ $subject_vertical->subject_vertical }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Subject Vertical Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('subjectvertical_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="department_id" :value="__('Department')" />
            <x-input-select id="department_id" wire:model.live="department_id" name="department_id" class="text-center @error('department_id') is-invalid @enderror w-full mt-1" :value="old('department_id', $department_id)" required autofocus autocomplete="department_id">
                <x-select-option class="text-start" hidden> -- Select Department -- </x-select-option>
                @forelse ($departments as $departmentid => $departmentname )
                    <x-select-option wire:key="{{ $departmentid }}" value="{{ $departmentid }}" class="text-start"> {{ $departmentname }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Departments Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="course_id" :value="__('Programme')" />
            <x-input-select id="course_id" wire:model.live="course_id" name="course_id" class="text-center w-full mt-1" :value="old('course_id', $course_id)" required autocomplete="course_id">
                <x-select-option class="text-start" hidden> -- Select Programme -- </x-select-option>
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
            <x-input-label for="subject_sem" :value="__('Subject Semester')" />
            <x-input-select id="subject_sem" wire:model.live="subject_sem" name="subject_sem" class="text-center @error('subject_sem') is-invalid @enderror w-full mt-1" :value="old('subject_sem', $subject_sem)" required autofocus autocomplete="subject_sem">
                <x-select-option class="text-start" hidden> -- Select Subject Semester -- </x-select-option>
                @forelse ($semesters as $semesterid => $semestername)
                    <x-select-option wire:key="{{ $semesterid }}" value="{{ $semesterid }}" class="text-start"> {{ $semestername }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Semesters Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('subject_sem')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_id" :value="__('Select Subject')" />
            <x-input-select id="subject_id" wire:model.live="subject_id" name="subject_id" class="text-center w-full mt-1" :value="old('subject_id', $subject_id)" required autocomplete="subject_id">
                <x-select-option class="text-start" hidden> -- Select Subject -- </x-select-option>
                @forelse ($subjects as $subject)
                    <x-select-option wire:key="{{ $subject->id }}" value="{{ $subject->id }}" class="text-start"> {{ $subject->subject_name }}</x-select-option>
                @empty
                    <x-select-option class="text-start">Subjects Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('subject_id')" class="mt-1" />
        </div>
    </div>
    <x-form-btn>Submit</x-form-btn>
</div>
