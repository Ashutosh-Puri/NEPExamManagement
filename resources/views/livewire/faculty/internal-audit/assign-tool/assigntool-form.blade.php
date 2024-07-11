<div>
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
            Internal Tool Document's
        </div>
        <x-form wire:submit="save()">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="academicyear_id" :value="__('Academic Year')" />
                    <x-input-select id="academicyear_id" wire:model.live="academicyear_id" name="academicyear_id" class="text-center @error('academicyear_id') is-invalid @enderror w-full mt-1" :value="old('academicyear_id', $academicyear_id)" required autofocus autocomplete="academicyear_id">
                        <x-select-option class="text-start" hidden> -- Select Academic Year -- </x-select-option>
                        @forelse ($academicyears as $academicyearid => $academicyearname)
                            <x-select-option wire:key="{{ $academicyearid }}" value="{{ $academicyearid }}" class="text-start">{{ $academicyearname }}</x-select-option>
                        @empty
                            <x-select-option class="text-start">Academic Years Not Found</x-select-option>
                        @endforelse
                    </x-input-select>
                    <x-input-error :messages="$errors->get('academicyear_id')" class="mt-2" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="course_id" :value="__('Programme')" />
                    <x-input-select id="course_id" wire:model.live="course_id" name="course_id" class="text-center w-full mt-1" :value="old('course_id', $course_id)" required autocomplete="course_id">
                        <x-select-option class="text-start" hidden> -- Select Programme -- </x-select-option>
                        @forelse ($courses as $course)
                            <x-select-option wire:key="{{ $course->id }}" value="{{ $course->id }}" class="text-start"> {{ $course->course_name }} </x-select-option>
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
                        @forelse ($patternclasses as $pattern_class)
                            <x-select-option wire:key="{{ $pattern_class->id }}" value="{{ $pattern_class->id }}" class="text-start"> {{ $pattern_class->classyear_name ?? '-' }} {{ $pattern_class->course_name ?? '-' }} {{ $pattern_class->pattern_name ?? '-' }}</x-select-option>
                        @empty
                            <x-select-option class="text-start">Pattern Classes Not Found</x-select-option>
                        @endforelse
                    </x-input-select>
                    <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="subject_id" :value="__('Select Subject')" />
                    <x-input-select id="subject_id" wire:model.live="subject_id" name="subject_id" class="text-center w-full mt-1" :value="old('subject_id', $subject_id)" required autocomplete="subject_id">
                        <x-select-option class="text-start" hidden> -- Select Subject -- </x-select-option>
                        @forelse ($subjects as $subject)
                            <x-select-option wire:key="{{ $subject->id }}" value="{{ $subject->id }}" class="text-start"> {{ $subject->subject_code }} - {{ $subject->subject_name }}</x-select-option>
                        @empty
                            <x-select-option class="text-start">Subjects Not Found</x-select-option>
                        @endforelse
                    </x-input-select>
                    <x-input-error :messages="$errors->get('subject_id')" class="mt-1" />
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-1">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="assignedtool_id" :value="__('CIA Tools Name')" />
                    <x-input-select multiple id="assignedtool_id" wire:model.live="selected_tools" name="assignedtool_id" class="text-center w-full mt-1 h-44" :value="old('assignedtool_id', $assignedtool_id)" required autocomplete="assignedtool_id">
                        <x-select-option class="text-start" hidden> -- Select Tool -- </x-select-option>
                        @forelse ($internaltools as $internaltool)
                            <x-select-option wire:key="{{ $internaltool->id }}" value="{{ $internaltool->id }}" class="text-start">{{ $internaltool->toolname }} </x-select-option>
                        @empty
                            <x-select-option class="text-start">Tools Not Found</x-select-option>
                        @endforelse
                    </x-input-select>
                    <x-input-error :messages="$errors->get('assignedtool_id')" class="mt-1" />
                </div>
            </div>
            <x-form-btn>Submit</x-form-btn>
        </x-form>
    </div>
</div>
