<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Subject Common Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectvertical_id" :value="__('Subject Vertical')" />
            <x-input-select id="subjectvertical_id" wire:model.live="subjectvertical_id" name="subjectvertical_id" class="text-center @error('subjectvertical_id') is-invalid @enderror w-full mt-1" :value="old('subjectvertical_id', $subjectvertical_id)" required autofocus autocomplete="subjectvertical_id">
                <x-select-option class="text-start" hidden> -- Select Subject Vertical -- </x-select-option>
                @forelse ($subject_verticals as $subjectverticalid => $subjectverticalname)
                    <x-select-option wire:key="{{ $subjectverticalid }}" value="{{ $subjectverticalid }}" class="text-start"> {{ $subjectverticalname }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Subject Verticals Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('subjectvertical_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="course_id" :value="__('Programme')" />
            <x-input-select id="course_id" wire:model.live="course_id" name="course_id" class="text-center w-full mt-1" :value="old('course_id', $course_id)" required autocomplete="course_id">
                <x-select-option class="text-start" hidden> -- Select Programme -- </x-select-option>
                @forelse ($courses as $courseid => $coursename)
                    <x-select-option wire:key="{{ $courseid }}" value="{{ $courseid }}" class="text-start"> {{ $coursename }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Programmes Not Found</x-select-option>
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
            <x-input-label for="subject_name_prefix" :value="__('Subject Name Prefix')" />
            <x-text-input id="subject_name_prefix" type="text" wire:model="subject_name_prefix" name="subject_name_prefix" placeholder="Subject Name Prefix" class=" @error('subject_name_prefix') is-invalid @enderror w-full mt-1" :value="old('subject_name_prefix', $subject_name_prefix)" required autofocus autocomplete="subject_name_prefix" />
            <x-input-error :messages="$errors->get('subject_name_prefix')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="classyear_id" :value="__('Select Class Year')" />
            <x-input-select id="classyear_id" wire:model.live="classyear_id" name="classyear_id" class="text-center @error('classyear_id') is-invalid @enderror w-full mt-1" :value="old('classyear_id', $classyear_id)" required autofocus autocomplete="classyear_id">
                <x-select-option class="text-start" hidden> -- Select Class Year -- </x-select-option>
                @forelse ($class_years as $classyearid => $classyearname)
                    <x-select-option wire:key="{{ $classyearid }}" value="{{ $classyearid }}" class="text-start"> {{ $classyearname }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Class Years Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('classyear_id')" class="mt-2" />
        </div>
    </div>
</div>


<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Subject Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_name" :value="__('Subject Name')" />
            <x-text-input id="subject_name" type="text" wire:model="subject_name" name="subject_name" placeholder="Subject Name" class=" @error('subject_name') is-invalid @enderror w-full mt-1" :value="old('subject_name', $subject_name)" required autofocus autocomplete="subject_name" oninput="this.value = this.value.toUpperCase()" />
            <x-input-error :messages="$errors->get('subject_name')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_code" :value="__('Subject Code')" />
            <x-text-input id="subject_code" type="text" wire:model="subject_code" name="subject_code" placeholder="Subject Code" class=" @error('subject_code') is-invalid @enderror w-full mt-1" :value="old('subject_code', $subject_code)" required autofocus autocomplete="subject_code" />
            <x-input-error :messages="$errors->get('subject_code')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subjectcategory_id " :value="__('Subject Category')" />
            <x-input-select id="subjectcategory_id" wire:model.live="subjectcategory_id" name="subjectcategory_id" class="text-center @error('subjectcategory_id') is-invalid @enderror w-full mt-1" :value="old('subjectcategory_id', $subjectcategory_id)" required autofocus autocomplete="subjectcategory_id">
                <x-select-option class="text-start" hidden> -- Select Subject Category -- </x-select-option>
                @forelse ($subject_categories as $subjectcategoryid => $subjectcategoryname)
                    <x-select-option wire:key="{{ $subjectcategoryid }}" value="{{ $subjectcategoryid }}" class="text-start"> {{ $subjectcategoryname }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Subject Types Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('subjectcategory_id')" class="mt-2" />
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_type" :value="__('Subject Type')" />
            <x-input-select id="subject_type" wire:model.live="subject_type" name="subject_type" class="text-center @error('subject_type') is-invalid @enderror w-full mt-1" :value="old('subject_type', $subject_type)" required autofocus autocomplete="subject_type">
                <x-select-option class="text-start" hidden> -- Select Subject Type -- </x-select-option>
                @forelse ($subject_types as $subject_type)
                    <x-select-option wire:key="{{ $subject_type->id }}" value="{{ $subject_type->subjecttype_id }}" class="text-start">{{ $subject_type->subjecttype->description }}</x-select-option>
                @empty
                    <x-select-option class="text-start">Subject Types Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('subject_type')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_credit" :value="__('Subject Credit')" />
            <x-input-select id="subject_credit" wire:model.live="subject_credit" name="subject_credit" class="text-center @error('subject_credit') is-invalid @enderror w-full mt-1" :value="old('subject_credit', $subject_credit)" required autofocus autocomplete="subject_credit">
                <x-select-option class="text-start" hidden> -- Select Subject Credit -- </x-select-option>
                @forelse ($subject_credits as $subjectcreditid => $subjectcredit)
                    <x-select-option wire:key="{{ $subjectcreditid }}" value="{{ $subjectcreditid }}" class="text-start"> {{ $subjectcredit }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Subject Credits Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('subject_credit')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="is_panel" :value="__('Exam Panel')" />
            <x-input-select id="is_panel" wire:model="is_panel" name="is_panel" class="text-center @error('is_panel') is-invalid @enderror w-full mt-1" :value="old('is_panel', $is_panel)" required autofocus autocomplete="is_panel">
                <x-select-option class="text-start" hidden> -- Select Exam Panel -- </x-select-option>
                <x-select-option class="text-start" value="0">NO</x-select-option>
                <x-select-option class="text-start" value="1">YES</x-select-option>
            </x-input-select>
            <x-input-error :messages="$errors->get('is_panel')" class="mt-2" />
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="no_of_sets" :value="__('No Of Sets')" />
            <x-input-select id="no_of_sets" wire:model="no_of_sets" name="no_of_sets" class="text-center @error('no_of_sets') is-invalid @enderror w-full mt-1" :value="old('no_of_sets', $no_of_sets)" required autofocus autocomplete="no_of_sets">
                <x-select-option class="text-start" hidden> -- Select No Of Sets -- </x-select-option>
                <x-select-option class="text-start" value="1">1</x-select-option>
                <x-select-option class="text-start" value="2">2</x-select-option>
                <x-select-option class="text-start" value="3">3</x-select-option>
            </x-input-select>
            <x-input-error :messages="$errors->get('no_of_sets')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="subject_order" :value="__('Subject Order')" />
            <x-text-input id="subject_order" type="number" wire:model="subject_order" name="subject_order" placeholder="Subject Order" class=" @error('subject_order') is-invalid @enderror w-full mt-1" :value="old('subject_order', $subject_order)" required autofocus autocomplete="subject_order" />
            <x-input-error :messages="$errors->get('subject_order')" class="mt-2" />
        </div>
    </div>
</div>

@if ($type['IE'])
    @include('livewire.faculty.subject.type-ie')
@elseif ($type['IP'])
    @include('livewire.faculty.subject.type-ip')
@elseif ($type['IG'])
    @include('livewire.faculty.subject.type-ig')
@elseif ($type['I'])
    @include('livewire.faculty.subject.type-i')
@elseif ($type['P'])

@elseif ($type['G'])

@elseif ($type['IEP'])
    @include('livewire.faculty.subject.type-iep')
@elseif ($type['IEG'])
    @include('livewire.faculty.subject.type-ieg')
@elseif ($type['E'])

@endif
