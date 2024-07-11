<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Block Allocation
        <x-spinner />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="block_id" :value="__('Select Block')" />
            <x-required />
            <x-input-select id="block_id" wire:model="block_id" name="block_id" class="text-center w-full mt-1" :value="old('block_id', $block_id)" autocomplete="block_id">
                <x-select-option class="text-start" hidden> -- Select Block -- </x-select-option>
                @forelse ($blocks as $blockid => $blockname)
                <x-select-option wire:key="{{ $blockid }}" value="{{$blockid }}" class="text-start"> {{ $blockname }} </x-select-option>
                @empty
                <x-select-option class="text-start">Block Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('block_id')" class="mt-1" />
        </div>

        <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="classroom_id" :value="__('Select Classroom')" />
            <x-required />
            <x-input-select id="classroom_id" wire:model="classroom_id" name="classroom_id" class="text-center w-full mt-1" :value="old('classroom_id', $classroom_id)" autocomplete="classroom_id">
                <x-select-option class="text-start" hidden> -- Select Classroom -- </x-select-option>
                @forelse ($classrooms as $cid => $cname)
                <x-select-option wire:key="{{ $cid }}" value="{{$cid }}" class="text-start"> {{ $cname }} </x-select-option>
                @empty
                <x-select-option class="text-start">Classroom Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('classroom_id')" class="mt-1" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="exampatternclass_id" :value="__('Select Exam Pattern Classes')" />
            <x-required />
            <x-input-select id="exampatternclass_id" wire:model.live="exampatternclass_id" name="exampatternclass_id" class="text-center w-full mt-1" :value="old('exampatternclass_id', $exampatternclass_id)" required autocomplete="exampatternclass_id">
                <x-select-option class="text-start" hidden> -- Select Exam Pattern Classes -- </x-select-option>
                @forelse ($exampatternclasses as $exampatternclass)
                <x-select-option wire:key="{{ $exampatternclass->id }}" value="{{ $exampatternclass->id }}" class="text-start">{{ $exampatternclass->patternclass->pattern->pattern_name }} {{ $exampatternclass->patternclass->courseclass->classyear->classyear_name??'-' }} {{ $exampatternclass->patternclass->courseclass->course->course_name }} </x-select-option>
                @empty
                <x-select-option class="text-start">Course Classes Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('exampatternclass_id')" class="mt-1" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_id" :value="__('Subject')" />
            <x-required />
            <x-input-select id="subject_id" wire:model="subject_id" name="subject_id" class="text-center w-full mt-1" :value="old('subject_id',$subject_id)" required autofocus autocomplete="subject_id">
                <x-select-option class="text-start" hidden> -- Select Subject -- </x-select-option>
                @foreach ($subjects as $sid=>$sname)
                <x-select-option wire:key="{{ $sid }}" value="{{ $sid }}" class="text-start">{{ $sname }}</x-select-option>
                @endforeach
            </x-input-select>
            <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
        </div>

        <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="faculty_id" :value="__('Select Faculty')" />
            <x-required />
            <x-input-select id="faculty_id" wire:model="faculty_id" name="faculty_id" class="text-center w-full mt-1" :value="old('faculty_id', $faculty_id)" autocomplete="faculty_id">
                <x-select-option class="text-start" hidden> -- Select Faculty -- </x-select-option>
                @forelse ($faculties as $fid => $fname)
                <x-select-option wire:key="{{ $fid }}" value="{{$fid }}" class="text-start"> {{ $fname }} </x-select-option>
                @empty
                <x-select-option class="text-start">Faculty Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('faculty_id')" class="mt-1" />
        </div>

        <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="college_id" :value="__('Select College')" />
            <x-required />
            <x-input-select id="college_id" wire:model="college_id" name="college_id" class="text-center w-full mt-1" :value="old('college_id', $college_id)" autocomplete="college_id">
                <x-select-option class="text-start" hidden> -- Select College -- </x-select-option>
                @forelse ($colleges as $collegeid => $collegename)
                <x-select-option wire:key="{{ $collegeid }}" value="{{$collegeid }}" class="text-start"> {{ $collegename }} </x-select-option>
                @empty
                <x-select-option class="text-start">Colleges Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('college_id')" class="mt-1" />
        </div>


        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="status" :value="__('Status')" />
            <x-required />
            <x-input-select id="status" wire:model="status" name="status" class="text-center  w-full mt-1" :value="old('status',$status)" required autocomplete="status">
                <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
                <x-select-option class="text-start" value="0">Inactive</x-select-option>
                <x-select-option class="text-start" value="1">Active</x-select-option>
            </x-input-select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    </div>
    <x-form-btn wire:loading.attr="disable">
        Submit
    </x-form-btn>
</div>
