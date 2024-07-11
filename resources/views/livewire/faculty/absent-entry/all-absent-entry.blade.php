<div>
    <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Absent Entry" />
    </x-breadcrumb.breadcrumb>
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <div class="bg-primary px-2 py-3 font-semibold text-white dark:text-light">
            Absent Student Entry
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
                <x-input-label for="subject_id" :value="__('Subject Name')" /><x-required />
                <x-input-select id="subject_id" wire:model.live="subject_id" name="subject_id" class="text-center w-full mt-1" :value="old('subject_id', $subject_id)" required autocomplete="subject_id">
                    <x-select-option hidden>--- Select Subject ---</x-select-option>
                    @forelse ($subjects as $subject)
                        <x-select-option wire:key="{{ $subject->id }}" value="{{ $subject->id }}" class="text-start"> {{ $subject->subject_code }} {{ $subject->subject_name }} </x-select-option>
                    @empty
                        <x-select-option class="text-start">Subjects Not Found</x-select-option>
                    @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('subject_id')" class="mt-1" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_type" :value="__('Subject Type')" /><x-required />
                <x-input-select id="subject_type" wire:model.live="subject_type" name="subject_type" class="text-center w-full mt-1" :value="old('subject_type', $subject_type)" required autocomplete="subject_type">
                    <x-select-option hidden>--- Select Subject Type ---</x-select-option>
                    @forelse ($subject_types as $subject_type)
                        <x-select-option wire:key="{{ $subject_type }}" value="{{ $subject_type }}" class="text-start"> {{ $subject_type == 'I' || $subject_type == 'IG' ? 'Internal' : ($subject_type == 'IGE' || $subject_type == 'IEG' ? 'External' : ($subject_type == 'G' ? 'Grade' : 'Practical')) }} </x-select-option>
                    @empty
                        <x-select-option class="text-start">Subject Types Not Found</x-select-option>
                    @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('subject_type')" class="mt-1" />
            </div>
            @if (isset($subject_type))
                @if (!is_null($batches))
                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="batch_id" :value="__('Batch')" /><x-required />
                        <x-input-select id="batch_id" wire:model.live="batch_id" name="batch_id" class="text-center w-full mt-1" :value="old('batch_id', $batch_id)" required autocomplete="batch_id">
                            <x-select-option hidden>--- Select Batch ---</x-select-option>
                            @forelse ($batches as $batch)
                                <x-select-option wire:key="{{ $batch->id }}" value="{{ $batch->id }}" class="text-start"> {{ $batch->created_at->year . $batch->subject_id . str_pad($batch->id, 5, '0', STR_PAD_LEFT) }} </x-select-option>
                            @empty
                                <x-select-option class="text-start">Batches Not Found</x-select-option>
                            @endforelse
                        </x-input-select>
                        <x-input-error :messages="$errors->get('batch_id')" class="mt-1" />
                    </div>
                @endif
            @endif
            @if (isset($batch_id))
                <div class="px-5 py-2 text-gray-600 dark:text-gray-400 sm:p-6">
                    <x-input-radio class="w-5 h-5 cursor-pointer" id="search_student" value="1" wire:model.live="option" name="show_student" />
                    <x-input-label for="search_student" class="inline mb-1 mx-2" :value="__('Search Student')" />
                    <x-input-radio class="w-5 h-5 cursor-pointer" id="show_absent_student" value="2" wire:model.live="option" name="show_student" />
                    <x-input-label for="show_absent_student" class="inline mb-1 mx-2" :value="__('Show Absent Student')" />
                </div>
            @endif
        </div>
        @if ($option === '1')
            <form wire:submit="searchseatno">
                @csrf
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="seatno" :value="__('Seat No')" />
                    <div class="flex flex-col md:flex-row items-center md:space-x-4 space-y-4 md:space-y-0 mt-1">
                        <div class="flex-1 w-full md:w-auto">
                            <x-text-input id="seatno" type="number" wire:model="seatno" name="seatno" placeholder="Enter seat no" class="@error('seatno') is-invalid @enderror w-full" :value="old('seatno', $seatno)" required autofocus autocomplete="seatno" />
                            <x-input-error :messages="$errors->get('seatno')" class="mt-2" />
                        </div>
                        <x-form-btn class="w-full md:w-auto">Submit</x-form-btn>
                    </div>
                </div>
            </form>
        @endif
        @if ($option === '2')
            <div class="m-2 overflow-x-scroll rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
                <div>
                    <x-table.frame x="0" s="0" r="0" p="0" sp="0">
                        <x-slot:body>
                            <x-table.table>
                                <x-table.thead>
                                    <x-table.tr>
                                        <x-table.th>Seat No</x-table.th>
                                        <x-table.th>PRN</x-table.th>
                                        <x-table.th>Student Name</x-table.th>
                                        <x-table.th>Batch No</x-table.th>
                                        <x-table.th>Absent</x-table.th>
                                        <x-table.th>N/A</x-table.th>
                                        <x-table.th>Action</x-table.th>
                                    </x-table.tr>
                                </x-table.thead>
                                <x-table.tbody>
                                    @forelse ($students as $stud)
                                        <x-table.tr>
                                            <x-table.td>
                                                {{ $stud->seatno }}
                                            </x-table.td>
                                            <x-table.td>
                                                {{ $stud->student->prn }}
                                            </x-table.td>
                                            <x-table.td>
                                                <x-table.text-scroll>
                                                    {{ $stud->student->student_name }}
                                                </x-table.text-scroll>
                                            </x-table.td>
                                            <x-table.td>
                                                <x-table.text-scroll>
                                                    {{ $int_batch->created_at->format('Y') . $int_batch->subject_id . str_pad($int_batch->id, 5, '0', STR_PAD_LEFT) }}
                                                </x-table.text-scroll>
                                            </x-table.td>
                                            <x-table.td>
                                                <input type="checkbox" class="my-1 h-5 w-5 border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-primary dark:text-primary dark:focus:ring-primary rounded-md shadow-sm dark:border-primary-darker border cursor-not-allowed" checked disabled />
                                            </x-table.td>
                                            <x-table.td>
                                                <input type="checkbox" class="my-1 h-5 w-5 border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-primary dark:text-primary dark:focus:ring-primary rounded-md shadow-sm dark:border-primary-darker border cursor-not-allowed" disabled />
                                            </x-table.td>
                                            <x-table.td>
                                                <x-table.delete i="0" wire:click="removeseatnoab({{ $stud->id }})">Remove</x-table.delete>
                                            </x-table.td>
                                        </x-table.tr>
                                    @empty
                                        <x-table.tr>
                                            <x-table.td colspan='9' class="text-center">No Data Found</x-table.td>
                                        </x-table.tr>
                                    @endforelse
                                </x-table.tbody>
                            </x-table.table>
                        </x-slot>
                        <x-slot:footer>
                            <x-table.paginate :data="$students" />
                        </x-slot>
                    </x-table.frame>
                </div>
            </div>
        @endif
    </div>
    @if (!is_null($examstud))
        <div class="mx-2 py-2">
            <x-table.table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th>PRN</x-table.th>
                        <x-table.th>Student Name</x-table.th>
                        <x-table.th>Action</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                    <x-table.tr>
                        <x-table.td>
                            {{ $examstud->first()->student->prn }}
                        </x-table.td>
                        <x-table.td>
                            {{ $examstud->first()->student->student_name }}
                        </x-table.td>
                        <x-table.td>
                            <button wire:click="saveseatnoab({{ $examstud->first()->id }})" class="py-1 w-24 h-18 bg-red-700 text-white font-semibold rounded">
                                Make Absent
                            </button>
                        </x-table.td>
                    </x-table.tr>
                </x-table.tbody>
            </x-table.table>
        </div>
    @endif
</div>
