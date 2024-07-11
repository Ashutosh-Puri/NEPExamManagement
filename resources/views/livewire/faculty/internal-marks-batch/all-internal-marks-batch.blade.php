<div>
    <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Create Batch" />
    </x-breadcrumb.breadcrumb>
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <div class="bg-primary px-2 py-3 font-semibold text-white dark:text-light">
            Internal Marks Batches
        </div>
        <x-form wire:submit="save()">
            <div class="grid grid-cols-1 md:grid-cols-1">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="patternclass_id" :value="__('Select Class')" />
                    <x-required />
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
                    <x-input-label for="subject_id" :value="__('Subject')" /><x-required />
                    <x-input-select id="subject_id" wire:model.live="subject_id" name="subject_id" class="text-center w-full mt-1" :value="old('subject_id', $subject_id)" required autocomplete="subject_id">
                        <x-select-option hidden> -- Select Subject -- </x-select-option>
                        @forelse ($subjects as $subject)
                            <x-select-option wire:key="{{ $subject->id }}" value="{{ $subject->id }}" class="text-start">{{ $subject->subject_code }} - {{ $subject->subject_name }}</x-select-option>
                        @empty
                            <x-select-option class="text-start">Subjects Not Found</x-select-option>
                        @endforelse
                    </x-input-select>
                    <x-input-error :messages="$errors->get('subject_id')" class="mt-1" />
                </div>
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="subject_type" :value="__('Subject Type')" /><x-required />
                    <x-input-select id="subject_type" wire:model.live="subject_type" name="subject_type" class="text-center w-full mt-1" :value="old('subject_type', $subject_type)" required autocomplete="subject_type">
                        <x-select-option hidden> -- Select Subject Type -- </x-select-option>
                        @forelse ($subject_types as $sub_type)
                            <x-select-option wire:key="{{ $sub_type }}" value="{{ $sub_type }}" class="text-start">{{ $sub_type == 'I' || $sub_type == 'IG' ? 'Internal' : ($sub_type == 'IGE' || $sub_type == 'IEG' ? 'External' : ($sub_type == 'G' ? 'Grade' : 'Practical')) }}</x-select-option>
                        @empty
                            <x-select-option class="text-start">Subjects Types Found</x-select-option>
                        @endforelse
                    </x-input-select>
                    <x-input-error :messages="$errors->get('subject_type')" class="mt-1" />
                </div>
                @if (isset($subject_type))
                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="seatnocount" :value="__('Student Count')" />
                        <x-input-show id="seatnocount" :value="$seatnocount" />
                    </div>
                    @if ($seatnocount == 0)
                        <span class="px-5 py-2 text-sm">{{ __('Batch already Created!!!!!!') }}</span>
                    @else
                        <div class="px-5 py-2 text-gray-600 dark:text-gray-400 sm:p-6">
                            <x-input-label for="batch_option" :value="__('Select Option')" /> <x-required /><br>
                            <x-input-radio class="w-5 h-5" id="option1" value="1" wire:model.live="batch_option" name="batch_option" />
                            <x-input-label for="option1" class="inline mb-1 mx-2" :value="__('All')" />
                            <x-input-radio class="w-5 h-5" id="option2" value="2" wire:model.live="batch_option" name="batch_option" />
                            <x-input-label for="option2" class="inline mb-1 mx-2" :value="__('By Series')" />
                            <x-input-radio class="w-5 h-5" id="option3" value="3" wire:model.live="batch_option" name="batch_option" />
                            <x-input-label for="option3" class="inline mb-1 mx-2" :value="__('By Selection')" />
                            <x-input-radio class="w-5 h-5" id="option4" value="4" wire:model.live="batch_option" name="batch_option" />
                            <x-input-label for="option4" class="inline mb-1 mx-2" :value="__('One By One')" />
                        </div>
                    @endif
                @endif
                @if ($batch_option == '1')
                @endif
                @if ($batch_option == '2')
                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="no_of_batch" :value="__('How many batches create?')" /><x-required />
                        <x-text-input id="no_of_batch" type="number" wire:model="no_of_batch" name="no_of_batch" placeholder="No of Batches" class=" @error('no_of_batch') is-invalid @enderror w-full mt-1" :value="old('no_of_batch', $no_of_batch)" required autofocus autocomplete="no_of_batch" />
                        <x-input-error :messages="$errors->get('no_of_batch')" class="mt-2" />
                    </div>
                @endif
            </div>
            @if ($batch_option == '3')
                <x-input-label class="pl-5" for="selectseatno" :value="__('Select Seat No')" /><x-required />
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-select id="fromseatno" wire:model="fromseatno" name="fromseatno" class="text-center w-full mt-1" :value="old('fromseatno', $fromseatno)" required autocomplete="fromseatno">
                            <x-select-option hidden>From</x-select-option>
                            @foreach ($seatno as $data)
                                <x-select-option wire:key="{{ $data }}" value="{{ $data }}" class="text-start">{{ $data }}</x-select-option>
                            @endforeach
                        </x-input-select>
                        <x-input-error :messages="$errors->get('fromseatno')" class="mt-2" />
                    </div>
                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-select id="toseatno" wire:model="toseatno" name="toseatno" class="text-center w-full mt-1" :value="old('toseatno', $toseatno)" required autocomplete="toseatno">
                            <x-select-option hidden>To</x-select-option>
                            @foreach ($seatno as $data)
                                <x-select-option wire:key="{{ $data }}" value="{{ $data }}" class="text-start">{{ $data }}</x-select-option>
                            @endforeach
                        </x-input-select>
                        <x-input-error :messages="$errors->get('toseatno')" class="mt-2" />
                    </div>
                </div>
            @endif
            @if ($batch_option == '4')
                <div class="px-5 py-1 grid grid-cols-12 md:grid-cols-12">
                    @foreach ($seatno as $data)
                        <div>
                            <x-input-checkbox class="w-5 h-5" id="checked_seatno.{{ $data }}" wire:model="checked_seatno.{{ $data }}" name="checked_seatno.{{ $data }}" />
                            <x-input-label for="checked_seatno.{{ $data }}" class="inline mb-1 mx-1" :value="$data" />
                        </div>
                    @endforeach
                </div>
            @endif
            @if (isset($batch_option))
                <x-form-btn>Submit</x-form-btn>
            @endif
        </x-form>
    </div>
    <div class="m-2 overflow-x-scroll rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <div>
            <x-table.table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th>Sr.No</x-table.th>
                        <x-table.th>Class</x-table.th>
                        <x-table.th>Subject</x-table.th>
                        <x-table.th>Subject Type</x-table.th>
                        <x-table.th>Batch No.</x-table.th>
                        <x-table.th>Seat No.</x-table.th>
                        <x-table.th>Action</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                @php
                    $i = 1;
                @endphp
                <x-table.tbody>
                    @forelse ($int_batches as $int_batch)
                        <x-table.tr>
                            <x-table.td>{{ $i++ }}</x-table.td>
                            <x-table.td>
                                <x-table.text-scroll>
                                    {{ isset($int_batch->exam_patternclass->patternclass->pattern->pattern_name) ? $int_batch->exam_patternclass->patternclass->pattern->pattern_name : '-' }}
                                    {{ isset($int_batch->exam_patternclass->patternclass->courseclass->classyear->classyear_name) ? $int_batch->exam_patternclass->patternclass->courseclass->classyear->classyear_name : '-' }}
                                    {{ isset($int_batch->exam_patternclass->patternclass->courseclass->course->course_name) ? $int_batch->exam_patternclass->patternclass->courseclass->course->course_name : '-' }}
                                </x-table.text-scroll>
                            </x-table.td>
                            <x-table.td>
                                <x-table.text-scroll>
                                    {{ isset($int_batch->subject->subject_code) ? $int_batch->subject->subject_code : '-' }}
                                    {{ isset($int_batch->subject->subject_name) ? $int_batch->subject->subject_name : '-' }}
                                </x-table.text-scroll>
                            </x-table.td>
                            <x-table.td>{{ $int_batch->subject_type == 'I' || $int_batch->subject_type == 'IG' ? 'Internal' : ($int_batch->subject_type == 'IGE' || $int_batch->subject_type == 'IEG' ? 'External' : 'Practical') }}</x-table.td>
                            <x-table.td>
                                <x-table.text-scroll>
                                    {{ $int_batch->created_at->format('Y') . $int_batch->subject_id . str_pad($int_batch->id, 5, '0', STR_PAD_LEFT) }}
                                </x-table.text-scroll>
                            </x-table.td>
                            <x-table.td class="whitespace-normal">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($int_batch->intbatchseatnoallocations->pluck('seatno') as $seatno)
                                        <span class="w-fit">{{ $seatno }}{{ !$loop->last ? ',' : '' }}</span>
                                    @endforeach
                                </div>
                            </x-table.td>
                            <x-table.td>
                                <x-table.delete wire:click="deleteconfirmation({{ $int_batch->id }})" />
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colSpan='7' class="text-center">No Data Found</x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table.table>
            <x-slot:footer>
                <x-table.paginate :data="$int_batches" />
            </x-slot>
        </div>
    </div>
</div>
