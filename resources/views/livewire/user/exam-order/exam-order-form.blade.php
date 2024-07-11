<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Exam Order
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="exampanel_id" :value="__('Select Exam Panel')" />
            <x-input-select id="exampanel_id" wire:model="exampanel_id" name="exampanel_id" class="text-center w-full mt-1" :value="old('exampanel_id', $exampanel_id)" required autocomplete="exampanel_id">
                <x-select-option class="text-start" hidden> -- Select Exam Panel -- </x-select-option>
                @forelse ($exampanels as $exampanel)
                <x-select-option wire:key="{{ $exampanel->id }}" value="{{ $exampanel->id }}" class="text-start"> {{ $exampanel->faculty->faculty_name ??'-' }} {{ $exampanel->subject->subject_name ??'-' }} {{ $exampanel->examorderpost->post_name ??'-' }} </x-select-option>
                @empty
                <x-select-option class="text-start">Subject Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('exampanel_id')" class="mt-1" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="exam_patternclass_id" :value="__('Select Exam Pattern Class')" />
            <x-input-select id="exam_patternclass_id" wire:model="exam_patternclass_id" name="exam_patternclass_id" class="text-center w-full mt-1" :value="old('exam_patternclass_id', $exam_patternclass_id)" required autocomplete="exam_patternclass_id">
                <x-select-option class="text-start" hidden> -- Select Exam Pattern Classes -- </x-select-option>
                @forelse ($exampatternclasses as $exam_pattern_class)
                <x-select-option wire:key="{{ $exam_pattern_class->id }}" value="{{ $exam_pattern_class->id }}" class="text-start"> {{ $exam_pattern_class->exam->exam_name ?? '-' }} {{ $exam_pattern_class->patternclass->pattern->pattern_name ?? '-' }} {{ $exam_pattern_class->patternclass->courseclass->classyear->classyear_name ?? '-' }} {{ $exam_pattern_class->patternclass->courseclass->course->course_name ?? '-' }} </x-select-option>
                @empty
                <x-select-option class="text-start">Exam Pattern Classes Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('exam_patternclass_id')" class="mt-1" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="description" :value="__(' Description')" />
            <x-required />
            <x-text-input id="description" type="text" wire:model="description" name="description" class="w-full mt-1" :value="old('description',$description)" required autofocus autocomplete="description" />
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="email_status" :value="__('Email Status')" />
            <x-required />
            <x-input-select id="email_status" wire:model="email_status" name="email_status" class="text-center  w-full mt-1" :value="old('email_status',$email_status)" required autocomplete="email_status">
                <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
                <x-select-option class="text-start" value="0">Not Sent</x-select-option>
                <x-select-option class="text-start" value="1">Sent</x-select-option>
            </x-input-select>
            <x-input-error :messages="$errors->get('email_status')" class="mt-2" />
        </div>
    </div>
</div>
<x-form-btn >Submit</x-form-btn>
</div>
