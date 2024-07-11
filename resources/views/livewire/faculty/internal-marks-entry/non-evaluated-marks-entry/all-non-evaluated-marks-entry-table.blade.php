<x-table.frame x="0" s="0" r="0" p="0" sp="0">
    <x-slot:body>
        <x-table.table>
            <x-table.thead>
                <x-table.tr>
                    <x-table.th>Seat No</x-table.th>
                    <x-table.th>PRN</x-table.th>
                    <x-table.th>Student Name</x-table.th>
                    <x-table.th>Marks</x-table.th>
                    @if ($this->appointed_batch->subject_type !== 'G')
                        <x-table.th>Max Marks</x-table.th>
                    @endif
                </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
                @forelse ($non_evaluated_marks_entries as $non_evaluated_marks_entry)
                    <x-table.tr>
                        <x-table.td>{{ $non_evaluated_marks_entry->seatno }}</x-table.td>
                        <x-table.td>{{ $non_evaluated_marks_entry->student->prn }}</x-table.td>
                        <x-table.td>{{ strtoupper($non_evaluated_marks_entry->student->student_name) }}</x-table.td>
                        {{-- @if ($this->appointed_batch->subject_type == 'G')
                            <x-table.td>
                                <x-input-select id="grade" wire:model.lazy="selectedGrade.{{ $eval_non_eval_marks_entry->student->id }}" name="grade" :value="old('grade', $grade)" required autofocus autocomplete="grade">
                                    <x-select-option class="text-start" hidden> -- Select Grade -- </x-select-option>
                                    @forelse ($cmdgrade11 as $key => $grade)
                                        <x-select-option wire:key="{{ $key }}" value="{{ $grade }}" class="text-start"> {{ $grade }} </x-select-option>
                                    @empty
                                        <x-select-option class="text-start">Grades Not Found</x-select-option>
                                    @endforelse
                                </x-input-select>
                                <x-input-error :messages="$errors->get('selectedGrade.' . $eval_non_eval_marks_entry->student->id)" class="mt-2" />
                            </x-table.td>
                        @endif --}}
                        @if ($this->appointed_batch->subject_type == 'I' || $this->appointed_batch->subject_type == 'IG')
                            <x-table.td>
                                <x-text-input type="number" name="marks" id="marks" wire:model.lazy="selectedMarks.{{ $non_evaluated_marks_entry->student->id }}" placeholder="Enter Marks" />
                                <x-input-error :messages="$errors->get('selectedMarks.' . $non_evaluated_marks_entry->student->id)" class="mt-2" />
                            </x-table.td>
                            <x-table.td>
                                {{ $appointed_batch->subject->subject_maxmarks_int }}
                            </x-table.td>
                        @endif
                        {{-- @if ($this->appointed_batch->subject_type == 'IEG')
                            <x-table.td>
                                <x-text-input type="number" name="marks" id="marks" wire:model.lazy="selectedMarks.{{ $eval_non_eval_marks_entry->student->id }}" placeholder="Enter Marks" />
                                <x-input-error :messages="$errors->get('selectedMarks.' . $eval_non_eval_marks_entry->student->id)" class="mt-2" />
                            </x-table.td>
                            <x-table.td>
                                {{ $appointed_batch->subject->subject_maxmarks_ext }}
                            </x-table.td>
                        @endif --}}
                        {{-- @if ($this->appointed_batch->subject_type == 'P')
                            <x-table.td>
                                <input type="checkbox" class="my-1 h-5 w-5 border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-primary dark:text-primary focus:border-primary dark:focus:border-primary focus:ring-primary dark:focus:ring-primary rounded-md shadow-sm dark:border-primary-darker border cursor-not-allowed'" wire:model="selectedCmb.{{ $eval_non_eval_marks_entry->student->id }}" value="{{ $eval_non_eval_marks_entry->student->id }}" {{ in_array($eval_non_eval_marks_entry->student->id, $selectedCmb) ? 'checked' : '' }} disabled />
                            </x-table.td>
                            <x-table.td>
                                <x-input-checkbox class="w-5 h-5 cursor-not-allowed" type="checkbox" disabled />
                            </x-table.td>
                            @if ($selectedMarks[$eval_non_eval_marks_entry->student->id] === 'Ab')
                                <x-table.td>
                                    <x-text-input type="number" name="marks" id="marks" wire:model.lazy="selectedMarks.{{ $eval_non_eval_marks_entry->student->id }}" class="bg-gray-400 cursor-not-allowed" placeholder="AB" disabled />
                                    <x-input-error :messages="$errors->get('selectedMarks.' . $eval_non_eval_marks_entry->student->id)" class="mt-2" />
                                </x-table.td>
                            @else
                                <x-table.td>
                                    <x-text-input type="number" name="marks" id="marks" wire:model.lazy="selectedMarks.{{ $eval_non_eval_marks_entry->student->id }}" placeholder="Enter Marks" />
                                    <x-input-error :messages="$errors->get('selectedMarks.' . $eval_non_eval_marks_entry->student->id)" class="mt-2" />
                                </x-table.td>
                            @endif
                            </td>
                            <td>
                                @switch($appointed_batch->subject->subject_type)
                                    @case('IEG')
                                        {{ $appointed_batch->subject->subject_maxmarks_ext }}
                                    @break;
                                    @case('IP')
                                        {{ $appointed_batch->subject->subject_maxmarks_ext }}
                                    @break;
                                    @case('IEP')
                                        {{ $appointed_batch->subject->subject_maxmarks_intpract }}
                                    @break;
                                @endswitch
                            </td>
                        @endif --}}
                    </x-table.tr>
                @empty
                    @if ($this->appointed_batch->subject_type == 'G')
                        <x-table.tr>
                            <x-table.td colspan='4' class="text-center">No Data Found</x-table.td>
                        </x-table.tr>
                    @else
                        <x-table.tr>
                            <x-table.td colspan='5' class="text-center">No Data Found</x-table.td>
                        </x-table.tr>
                    @endif
                @endforelse
            </x-table.tbody>
        </x-table.table>
    </x-slot>
    <x-slot:footer>
        <x-pagination-links :paginator="$non_evaluated_marks_entries" :prev="1" />
    </x-slot>
</x-table.frame>
