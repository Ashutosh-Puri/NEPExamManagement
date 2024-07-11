<div>
    <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Appoint Batch" />
    </x-breadcrumb.breadcrumb>
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <div class="bg-primary px-2 py-3 font-semibold text-white dark:text-light">
            Appoint Internal Marks Batches
        </div>
        <div class="grid grid-cols-1 md:grid-cols-1">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="patternclass_id" :value="__('Select Class')" /><x-required />
                <x-input-select id="patternclass_id" wire:model.live="patternclass_id" name="patternclass_id" class="text-center w-full mt-1" :value="old('patternclass_id', $patternclass_id)" required autocomplete="patternclass_id">
                    <x-select-option class="text-start" hidden> -- Select Class -- </x-select-option>
                    @php
                        $valid_pattern_classes = $patternclasses->filter(function ($pattern_class) use ($currentdate) {
                            $last_exam_pattern_class = $pattern_class->exampatternclasses->last();
                            return $last_exam_pattern_class && $currentdate->between($last_exam_pattern_class->intmarksstart_date, $last_exam_pattern_class->intmarksend_date);
                        });
                    @endphp
                    @if ($valid_pattern_classes->isEmpty())
                        <x-select-option class="text-start">Pattern Classes Not Found</x-select-option>
                    @else
                        @foreach ($valid_pattern_classes as $pattern_class)
                            <x-select-option wire:key="{{ $pattern_class->id }}" value="{{ $pattern_class->id }}" class="text-start">
                                {{ $pattern_class->classyear_name ?? '-' }} {{ $pattern_class->course_name ?? '-' }} {{ $pattern_class->pattern_name ?? '-' }}
                            </x-select-option>
                        @endforeach
                    @endif
                </x-input-select>
                <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="department_id" :value="__('Department')" />
                <x-input-select id="department_id" wire:model.live="department_id" name="department_id" class="text-center w-full mt-1" :value="old('department_id', $department_id)" required autofocus autocomplete="department_id">
                    <x-select-option class="text-start" hidden> -- Select Department -- </x-select-option>
                    @forelse ($departments as $department_id => $department_name)
                        <x-select-option wire:key="{{ $department_id }}" value="{{ $department_id }}" class="text-start"> {{ $department_name }} </x-select-option>
                    @empty
                        <x-select-option class="text-start">Departments Not Found</x-select-option>
                    @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="faculty_id" :value="__('Select Teacher')" />
                <x-input-select id="faculty_id" wire:model.live="faculty_id" name="faculty_id" class="text-center w-full mt-1" :value="old('faculty_id', $faculty_id)" required autocomplete="faculty_id">
                    <x-select-option hidden> -- Select Teacher -- </x-select-option>
                    @forelse ($faculties as $faculty_id => $faculty_name)
                        <x-select-option wire:key="{{ $faculty_id }}" value="{{ $faculty_id }}" class="text-start"> {{ $faculty_name }} </x-select-option>
                    @empty
                        <x-select-option class="text-start">Teachers Not Found</x-select-option>
                    @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('faculty_id')" class="mt-1" />
            </div>
            @if (isset($faculty_id))
                @if (!is_null($faculty_data))
                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-white flex flex-col space-y-2">
                        <div class="flex items-center">
                            <label for="faculty_data" class="mr-[10px]">Teacher</label>
                            <span class="mr-2">:</span>
                            <span>{{ $faculty_data->faculty_name }}</span>
                        </div>
                        <div class="flex items-center">
                            <label for="faculty_data" class="mr-[24px]">Email</label>
                            <span class="mr-2">:</span>
                            <span>{{ $faculty_data->email }}</span>
                        </div>
                        <div class="flex items-center">
                            <label for="faculty_data" class="mr-[14px]">Mobile</label>
                            <span class="mr-2">:</span>
                            <span>{{ $faculty_data->mobile_no }}</span>
                        </div>
                    </div>

                    <div class="flex space-x-4 px-5 py-2 text-sm">
                        <button type="button" wire:click.prevent="showbatch" class="w-64 py-1 h-18 bg-green-500 hover:bg-green-700 text-white font-bold rounded">Show Batches To Appoint</button>
                        <button type="button" wire:click.prevent="showbatchallocation" class="w-48 py-1 h-18 bg-green-500 hover:bg-green-700 text-white font-bold rounded">Show Appointed Batches</button>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @if ($a === 1)
        <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
            <x-table.table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th>Sr.No</x-table.th>
                        <x-table.th>Class</x-table.th>
                        <x-table.th>Subject</x-table.th>
                        <x-table.th>Subject Type</x-table.th>
                        <x-table.th>Batch No.</x-table.th>
                        <x-table.th>Select Subject</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                    @php
                        $i = 1;
                    @endphp
                    @forelse ($batches as $batch)
                        <x-table.tr>
                            <x-table.td>{{ $i++ }}</x-table.td>
                            <x-table.td>
                                <x-table.text-scroll>
                                    {{ isset($batch->exam_patternclass->patternclass->pattern->pattern_name) ? $batch->exam_patternclass->patternclass->pattern->pattern_name : '-' }}
                                    {{ isset($batch->exam_patternclass->patternclass->courseclass->classyear->classyear_name) ? $batch->exam_patternclass->patternclass->courseclass->classyear->classyear_name : '-' }}
                                    {{ isset($batch->exam_patternclass->patternclass->courseclass->course->course_name) ? $batch->exam_patternclass->patternclass->courseclass->course->course_name : '-' }}
                                </x-table.text-scroll>
                            </x-table.td>
                            <x-table.td>
                                <x-table.text-scroll>
                                    {{ isset($batch->subject->subject_code) ? $batch->subject->subject_code : '-' }}
                                    {{ isset($batch->subject->subject_name) ? $batch->subject->subject_name : '-' }}
                                </x-table.text-scroll>
                            </x-table.td>
                            <x-table.td>{{ $batch->subject_type == 'I' || $batch->subject_type == 'IG' ? 'Internal' : ($batch->subject_type == 'IGE' || $batch->subject_type == 'IEG' ? 'External' : ($batch->subject_type == 'G' ? 'Grade' : 'Practical')) }}
                            </x-table.td>
                            <x-table.td>
                                <x-table.text-scroll>
                                    {{ $batch->created_at->format('Y') . $batch->subject_id . str_pad($batch->id, 5, '0', STR_PAD_LEFT) }}
                                </x-table.text-scroll>
                            </x-table.td>
                            <x-table.td>
                                <input type="checkbox" class="my-1 w-5 h-5 border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-primary dark:text-primary focus:border-primary dark:focus:border-primary focus:ring-primary dark:focus:ring-primary rounded-md shadow-sm dark:border-primary-darker border" id="checked_batches.{{ $batch->id }}" wire:model.defer="checked_batches.{{ $batch->id }}" wire:model.defer="checked_batches.{{ $batch->id }}" value="checked_batches.{{ $batch->id }}" name="checked_batches.{{ $batch->id }}" />
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colSpan='6' class="text-center">No Data Found</x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table.table>
            @if ($batches->count() > 0)
                <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
                    <button wire:click="appointbatch" class="w-20 py-1 h-18 bg-green-500 hover:bg-green-700 text-white font-bold rounded">
                        Appoint
                    </button>
                </div>
            @endif
            <x-slot:footer>
                <x-table.paginate :data="$batches" />
            </x-slot>
        </div>
    @endif
    @if($a === 2)
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <x-table.table>
            <x-table.thead>
                <x-table.tr>
                    <x-table.th>Sr.No</x-table.th>
                    <x-table.th>Class</x-table.th>
                    <x-table.th>Subject</x-table.th>
                    <x-table.th>Subject Type</x-table.th>
                    <x-table.th>Batch No.</x-table.th>
                    <x-table.th>Action</x-table.th>
                </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
                @php
                    $i = 1;
                @endphp
                @forelse ($batches as $batch)
                    <x-table.tr>
                        <x-table.td>{{ $i++ }}</x-table.td>
                        <x-table.td>
                            <x-table.text-scroll>
                                {{ isset($batch->exam_patternclass->patternclass->pattern->pattern_name) ? $batch->exam_patternclass->patternclass->pattern->pattern_name : '-' }}
                                {{ isset($batch->exam_patternclass->patternclass->courseclass->classyear->classyear_name) ? $batch->exam_patternclass->patternclass->courseclass->classyear->classyear_name : '-' }}
                                {{ isset($batch->exam_patternclass->patternclass->courseclass->course->course_name) ? $batch->exam_patternclass->patternclass->courseclass->course->course_name : '-' }}
                            </x-table.text-scroll>
                        </x-table.td>
                        <x-table.td>
                            <x-table.text-scroll>
                                {{ isset($batch->subject->subject_code) ? $batch->subject->subject_code : '-' }}
                                {{ isset($batch->subject->subject_name) ? $batch->subject->subject_name : '-' }}
                            </x-table.text-scroll>
                        </x-table.td>
                        <x-table.td>{{ $batch->subject_type == 'I' || $batch->subject_type == 'IG' ? 'Internal' : ($batch->subject_type == 'IGE' || $batch->subject_type == 'IEG' ? 'External' : ($batch->subject_type == 'G' ? 'Grade' : 'Practical')) }}</x-table.td>
                        <x-table.td>
                            <x-table.text-scroll>
                                {{ $batch->created_at->format('Y') . $batch->subject_id . str_pad($batch->id, 5, '0', STR_PAD_LEFT) }}
                            </x-table.text-scroll>
                        </x-table.td>
                        <x-table.td>
                            <button wire:click="removebatch({{ $batch->id }})" class="w-20 py-1 h-18 bg-red-500 hover:bg-red-700 text-white font-bold rounded">
                                Remove
                            </button>
                        </x-table.td>
                    </x-table.tr>
                @empty
                    <x-table.tr>
                        <x-table.td colSpan='6' class="text-center">No Data Found</x-table.td>
                    </x-table.tr>
                @endforelse
            </x-table.tbody>
        </x-table.table>
        <x-slot:footer>
            <x-table.paginate :data="$batches" />
        </x-slot>
    </div>
    @endif
</div>
